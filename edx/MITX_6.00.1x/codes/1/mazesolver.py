# assumes rectangular maze, irregular dimensioned maze not supported.
# path starts with character A at last line first position
# path ends with character B at first line last position
# so a hypothetical straight line from A to B goes diagonally from bottom left to top right
# remember, along with maze characters, each line(except last) has also the newline character/s(2 char for windows)
# last line has the EOF character which rendered in an empty character
# you can see the newline character in console print, but not EOF

import os, platform, time

if platform.system() != 'Linux':
    raise Exception('Host system error. Only Linux supported.')

PATH_CHAR = '.'
WALL_CHAR = 'x'
MAZE_FILE_PATH = os.path.join(os.path.dirname(__file__), 'maze.txt')


def current_char(f):
    char = f.read(1)
    f.seek(f.tell() - 1)
    return char


def is_at_first_line(f):
    position = f.tell()
    f.seek(0)
    line1 = f.readline().strip('\n')
    f.seek(position)
    return position <= len(line1)


def is_at_last_line(f):
    position = f.tell()
    f.seek(0)
    last_line = f.readlines()[-1]
    last_char_position = f.tell()
    f.seek(position)
    return last_char_position - len(last_line) <= position <= last_char_position


def is_at_start(f):
    return f.tell() == 0


def is_at_end(f):
    position = f.tell()
    last_char_position = f.seek(0, 2)  # EOL character
    f.seek(position)
    return f.tell() == last_char_position


def is_wall(f, next_move=None):
    position = f.tell()
    if next_move:
        if (next_move == move_left and is_at_start(f)) or \
                (next_move == move_right and is_at_end(f)) or \
                (next_move == move_up and is_at_first_line(f)) or \
                (next_move == move_down and is_at_last_line(f)):  # check boundary perimeter
            return True
        else:  # check internal wall or line end
            next_move(f)
    char = f.read(1)
    f.seek(position)
    return char == WALL_CHAR or char == '\n' or char == ''


def is_goal(f, next_move):
    position = f.tell()
    char = f.read(1)
    f.seek(position)
    return char == 'B'


def move_up(f):
    position = f.tell()
    f.seek(0)
    line1 = f.readline()
    return f.seek(position - len(line1))


def move_down(f):
    position = f.tell()
    f.seek(0)
    line1 = f.readline()
    return f.seek(position + len(line1))


def move_left(f):
    return f.seek(f.tell() - 1)


def move_right(f):
    return f.seek(f.tell() + 1)


def get_reverse_move_method(move_method):
    if move_method == move_right:
        return move_left
    if move_method == move_left:
        return move_right
    if move_method == move_up:
        return move_down
    if move_method == move_down:
        return move_up


def move_reverse(move_method, f):
    get_reverse_move_method(move_method)(f)


def move_to_maze_start(f):
    f.seek(0)
    lines = f.readlines()
    last_line_first_char_position = (len(lines) - 1) * len(lines[0])
    return f.seek(last_line_first_char_position)


class GoalReachedException(BaseException):
    pass


class StackDataType:
    stack = []

    def __init__(self):
        self.stack = []  # this is pointless right? as we already declared the property with blank list

    def append(self, data):
        self.stack.append(data)

    def pop(self):
        return self.stack.pop()

    def is_empty(self):
        return len(self.stack) == 0


class QueueDataType:
    queue = []

    def __init__(self):
        self.queue = []  # this is pointless right? as we already declared the property with blank list

    def append(self, data):
        self.queue.append(data)

    def pop(self):
        val = self.queue[0]
        self.queue = self.queue[1:]
        return val

    def is_empty(self):
        return len(self.queue) == 0


class SquareTextualMaze:
    maze_file_path = None
    maze_solve_path = None
    discovery_seq = None
    explored = None
    junctions = None
    frontier = None

    def __init__(self, maze_file_path):
        self.maze_file_path = maze_file_path
        self.maze_solve_path = os.path.join(os.path.dirname(self.maze_file_path), 'results', str(time.time()))
        self.discovery_seq = [move_left, move_right, move_down, move_up]
        self.frontier = QueueDataType()
        self.explored = []
        self.junctions = []

        with open(self.maze_file_path) as f:  # now validate whether square maze
            lines = f.readlines()
            len_line1 = len(lines[0])
            f.seek(0)
            curr_line = 0
            for line in f:
                curr_line += 1
                if (curr_line < len(lines) and len(line) != len_line1) or \
                        (curr_line == len(lines) and len(line) != len_line1 - 1):  # EOF compensate for last line
                    raise Exception('Invalid maze! Not a square maze. Check line ' + str(curr_line))

    def is_explored(self, f):
        return f.tell() in self.explored

    def solve(self):
        with open(self.maze_file_path) as f:
            solution = f.read()
            move_to_maze_start(f)

            try:
                while True:  # one loop means one node(position) explored
                    # queue/stack processor
                    if not self.frontier.is_empty():
                        self.explored.append(f.tell()) if not is_at_start(f) else ''
                        next_node = self.frontier.pop()
                        f.seek(next_node)

                    # expand current node(explore next nodes), populate frontier
                    move_possible = 0  # how many nodes can be expanded from current node
                    for move_next in self.discovery_seq:
                        if is_goal(f, move_next):
                            raise GoalReachedException()
                        if is_wall(f, move_next):
                            continue
                        move_next(f)
                        if not self.is_explored(f):
                            move_possible += 1
                            self.frontier.append(f.tell())
                        move_reverse(move_next, f)  # because we are just testing move-ability to populate frontier

                    # dead end/junction processor
                    if move_possible == 0:
                        if len(self.junctions) == 0:
                            raise Exception('Maze design error, goal unreachable')
                        f.seek(self.junctions[-1])  # move to previous junction
                    elif move_possible > 1:
                        self.junctions.append(f.tell())
            except GoalReachedException:
                for pos in self.explored:
                    solution = solution[:pos - 1] + '*' + solution[pos + 1:]
                with open(self.maze_solve_path, 'w') as out_f:
                    out_f.write(solution)
                print(
                    'Goal Reached, {} nodes and {} joints explored. Used {}. Node expand sequence {}. Solution visualised at {}'
                        .format(len(self.explored), len(self.junctions), type(self.frontier).__name__,
                                [m.__name__ for m in self.discovery_seq], self.maze_solve_path))


SquareTextualMaze(MAZE_FILE_PATH).solve()
