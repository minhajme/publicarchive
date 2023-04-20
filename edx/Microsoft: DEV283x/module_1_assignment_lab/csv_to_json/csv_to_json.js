/* fetch csv from a url and transform it to json with csv data dict, ie.each csv entry to object of title=>value pair  */
const fs = require('fs');
const https = require('https');
const path = require('path');

let csv_src_url = 'https://prod-edxapp.edx-cdn.org/assets/courseware/v1/07d100219da1a726dad5eddb090fa215/asset-v1:Microsoft+DEV283x+2T2018+type@asset+block/customer-data.csv';
let output_json_file = path.join(__dirname, 'csv_to_json_output.json');
https.get(csv_src_url, (response) => {
    let csv_content = '';
    response.on('data', (chunk) => {
        csv_content += chunk;
    });
    response.on('end', () => {
        // create csv columns title list
        let csv_header = csv_content.substr(0, csv_content.indexOf('\n')).split(',');
        let csv_lines = csv_content.split('\n');
        delete csv_lines[0]; // only the csv data rows now
        csv_lines = csv_lines.filter((line) => {
            return !!line;
        });
        // now transform csv lines to list of dict
        let json = csv_lines.map((line) => {
            let line_columns = line.split(',');
            let dict = {};
            for (var i = 0; i < csv_header.length; i++) {
                dict[csv_header[i]] = line_columns[i];
            }
            return dict;
        });
        fs.writeFileSync(output_json_file, JSON.stringify(json), 'utf-8');
        console.log('output written to ' + output_json_file);
    });
}).on('error', () => {
    console.error('error fetching csv');
});