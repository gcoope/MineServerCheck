var auto_refresh = setInterval(
function ()
{
$('#main').load('mcping.php?_=' +Math.random()).fadeIn("slow");}, 5000); // refresh every 5 seconds