<?Php

namespace Resgef\SyncList\Models\ModelHelpers;

use Resgef\SyncList\Lib\Mysqli\MySQLi;
use Resgef\SyncList\Lib\Registry\Registry;
use Resgef\SyncList\Models\ListingModel\ListingModel;
use Resgef\SyncList\Models\ListingSourceModel\ListingSourceModel;
use Resgef\SyncList\Models\UserModel\UserModel;

/**
 *
 * Class Model
 * @important we return each database row as stdClass
 * @package Resgef\SyncList\Models\Model
 */
class ModelHelpers
{
    /** @var MySQLi the default database */
    private $db;

    /** @var Registry */
    private $registry;

    private $db_datetime_format;

    function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->db = $registry->get('db');

        $this->db_datetime_format = $registry->get('config')['db_datetime_format'];
    }

    function save_listing_source($url, $title, $user_id)
    {
        $url = $this->db->escape($url);
        $title = $this->db->escape($title);
        $this->db->query("insert into listing_source set url='$url', title='$title', user_id=$user_id");
        return $this->db->getLastId();
    }

    function delete_listing_source($id)
    {
        $this->db->query("delete from listing_source WHERE id=$id");
        return $this->db->countAffected();
    }

    /**
     * @param $user_id
     * @return null|array
     */
    function get_all_listing_source($user_id)
    {
        return $this->db->get_object("select * from listing_source where user_id=$user_id", ListingSourceModel::class)->rows;
    }

    /**
     * @param string $title
     * @param integer $source_id
     * @param string $item_url
     * @param string $price
     * @param string $posted
     * @return boolean
     */
    function save_listing($title, $source_id, $item_url, $price, $posted)
    {
        $title = $this->db->escape($title);
        $url = $this->db->escape($item_url);
        $price = $this->db->escape($price);
        if ($this->db->query("select * from listing where url='$url'")->num_rows) {
            $this->db->query("update listing set title='$title', price='$price' WHERE url='$url'");
            return true;
        } else {
            $this->db->query("insert into listing set title='$title', source_id=$source_id, price='$price', url='$url', posted='$posted'");
            return $this->db->getLastId();
        }
    }


    /**
     * TODO: make support for multi source
     * get the listing source
     * @return ListingSourceModel
     */
    function get_source()
    {
        return $this->db->get_object("SELECT * FROM listing_source", ListingSourceModel::class)->row;
    }

    /**
     * get the listings that are not viewed yet by the user
     * @param integer $user_id
     * @return array of ListingModel objects
     */
    function get_fresh_listings($user_id)
    {
        return $this->db->get_object("SELECT * FROM listing WHERE listing.id NOT IN ( select listing_id from listing_view where user_id=$user_id )", ListingModel::class)->rows;
    }

    function mark_viewed($listing_id, $user_id)
    {
        if (!$this->db->query("select * from listing_view where listing_id=$listing_id and user_id=$user_id")->num_rows) {
            $this->db->query("insert into listing_view set listing_id=$listing_id, user_id=$user_id");
            return $this->db->getLastId();
        } else {
            return true;
        }
    }

    /**
     * @param string $username
     * @param string $pass_hash
     * @param string $salt
     * @return boolean
     */
    function save_user($username, $pass_hash, $salt)
    {
        $username = $this->db->escape($username);
        $this->db->query("insert into users set username='$username', salt='$salt', password='$pass_hash'");
        return $this->db->getLastId();
    }

    /**
     * @param string $username
     * @return null|UserModel
     */
    function get_user($username)
    {
        $username = $this->db->escape($username);
        return $this->db->get_object("select * from users where username='$username'", UserModel::class)->row;
    }

    /**
     * @param $username
     * @return boolean
     */
    function user_exist($username)
    {
        $username = $this->db->escape($username);
        return $this->db->query("select * from users where username='$username'")->num_rows;
    }

    function remove_phinxlog($version)
    {
        $this->db->query("delete from phinxlog where version=$version");
        return $this->db->countAffected();
    }
}