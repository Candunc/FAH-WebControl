if ( typeof process.argv[2] == 'undefined' ) {
	console.log("Error! No api key given.")
	process.exit()
}



var webdriver = require('selenium-webdriver'),
    By = webdriver.By,
    until = webdriver.until;

var driver = new webdriver.Builder()
    .forBrowser('firefox')
    .build();


driver.get('https://www.cryptobullionpools.com/api.php?api_key=' + process.argv[2]);
driver.sleep(6000);
var a = driver.findElement(By.tagName("body"))
a.getInnerHtml().then(function(html) {
console.log(html);
});
driver.quit();
