var ts_fontsize="13px"
var longestmessage=1
for (i=2;i<line.length;i++){
if (line[i].length>line[longestmessage].length)
longestmessage=i
}
var tscroller_width=line[longestmessage].length
lines=line.length-1 //--Number of lines
//if IE 4+ or NS6
temp=""
nextline=1;
function animate(){
if (temp==line[nextline] & temp.length==line[nextline].length & nextline!=lines){
nextline++;
document.getElementById('recently_added').innerHTML=temp;
temp="";
setTimeout("nextstep()",3000)}
else if (nextline==lines & temp==line[nextline] & temp.length==line[nextline].length){
nextline=1;
document.getElementById('recently_added').innerHTML=temp;
temp="";
setTimeout("nextstep()",3000)}
else{
nextstep()
}}
function nextstep(){
temp = line[nextline];
document.getElementById('recently_added').innerHTML=temp
setTimeout("animate()",25)
}

window.onload=animate
