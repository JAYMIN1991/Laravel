/**
 * Created by flinnt-php-6 on 16/1/17.
 */

var path = require("path");
var publicPath = "public/";
var resourceJsPath = path.join(__dirname,"resources/assets/js/");
var resourceCssPath = "resources/assets/css";

var CommonChunkPlugin = require("./node_modules/webpack/lib/optimize/CommonsChunkPlugin.js");

module.exports = {
    entry: {
        common: [resourceJsPath+"jquery.js", resourceJsPath+"bootstrap.js", resourceJsPath+"angular.min.js", resourceJsPath+"angular-cookies.min.js", resourceJsPath+"validator.min.js"],
        login: [resourceJsPath+"login.js"]
    },
    output: {
        // Make sure to use [name] or [id] in output.filename
        //  when using multiple entry points
        path: path.join(__dirname,"public/js"),
        filename: "[name].js"
    },
    plugins:[
        new CommonChunkPlugin({
            name:"commons",
            chunks: ["common"]
        }),
        new CommonChunkPlugin({
            name:"login",
            chunks: ["commons", "login"]
        }),
    ],
};