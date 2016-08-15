
/*
var sSchemeSet=new Array();sSchemeSet["1"]=new Array("splH3","mColor2","mH3");sSchemeSet["2"]=new Array("s1Color","s1Color1","mColor1");sSchemeSet["3"]=new Array("hItemBgActiveColor");sSchemeSet["4"]=new Array("s1Color","splH3","s3Color","s3Color2");sSchemeSet["5"]=new Array("s1Color1","s3Color1","cbColor");

var sColorable=new Array();sColorable["splH3"]=".container_splash h3,.container_splash2 h3 { color:_COLOR_}";sColorable["mColor2"]="a:hover {color:_COLOR_}";sColorable["mH3"]=".container_content h3 { color:_COLOR_}";sColorable["s1Color"]="#splash {color:_COLOR_}#splash2 {color:_COLOR_}";sColorable["s1Color1"]="#splash .splashText a { color:_COLOR_}#splash2 .splashText a { color:_COLOR_}";sColorable["mColor1"]="a {color:_COLOR_}";sColorable["hItemBgActiveColor"]=".topmenu a:hover, .topmenu .item_active a { background-color:_COLOR_}";sColorable["s1Color"]="#splash {color:_COLOR_}#splash2 {color:_COLOR_}";sColorable["splH3"]=".container_splash h3,.container_splash2 h3 { color:_COLOR_}";sColorable["s3Color"]="#subsplash h3 { color:_COLOR_}";sColorable["s3Color2"]="#subsplash .splashText a:hover { color:_COLOR_}";sColorable["s1Color1"]="#splash .splashText a { color:_COLOR_}#splash2 .splashText a { color:_COLOR_}";sColorable["s3Color1"]="#subsplash .splashText a { color:_COLOR_}";sColorable["cbColor"]=".container_content{background-color:_COLOR_}";*/

var sSchemeSet=new Array();
// 1=darkest
sSchemeSet["1"]=new Array("topmenuItemBg","primaryButtonHover","contentLinkHover","websiteTitleOnWhiteTopThemes","colorSet1");
sSchemeSet["2"]=new Array("primaryButton","contentLink","topmenuItemBg_2","colorSet2","koColor");
sSchemeSet["3"]=new Array("colorSet3");
// 5=brightest
sSchemeSet["4"]=new Array("splashLink","colorSet4");
sSchemeSet["5"]=new Array("colorSet5","topmenuItemBg_5","koColorDark");

//#headerMenu.koMenu3 .topmenu a:hover span,#headerMenu.koMenu3 .topmenu .active a span{border-bottom:3px solid _COLOR_}

var sColorable=new Array();

sColorable["topmenuItemBg"]=".topmenu a:hover,.topmenu .active a{background-color:_COLOR_}";
sColorable["topmenuItemBg_2"]="#headerMenu.koMenu3 .topmenu a:hover,#headerMenu.koMenu3 .topmenu .active a{color:_COLOR_}#headerMenu.koMenu3 .topmenu a:hover span,#headerMenu.koMenu3 .topmenu .active a span{border-bottom:3px solid _COLOR_}.koTheme4 #website.logoRight #headerMenu.koMenu3 .topmenu a:hover span,.koTheme4 #website.logoRight #headerMenu.koMenu3 .topmenu .active a span {border:0;border-right:10px solid _COLOR_}.koTheme4 #website.logoLeft #headerMenu.koMenu3 .topmenu a:hover span,.koTheme4 #website.logoLeft #headerMenu.koMenu3 .topmenu .active a span {border:0;border-left:10px solid _COLOR_}"

// menu style #4

+"#headerMenu.koMenu4 {border-color:_COLOR_}#headerMenu.koMenu4 .topmenu a:hover,#headerMenu.koMenu4 .topmenu .active a{color:_COLOR_}"

// menu style #5

+"#headerMenu.koMenu5 {background:_COLOR_}";

sColorable["topmenuItemBg_5"]="#headerMenu.koMenu5 .topmenu a:hover,#headerMenu.koMenu5 .topmenu .active a,#headerMenu.koMenu5 .logoHolder h2{color:_COLOR_}"



sColorable["splashLink"]="#headerBanner a:not(.btn){color:_COLOR_}";
sColorable["splashButton"]="#headerBanner a.btn-primary{background-color:_COLOR_;border-color:_COLOR_}";
sColorable["splashButtonHover"]="#headerBanner a.btn-primary:hover{background-color:_COLOR_;border-color:_COLOR_}";
sColorable["primaryButton"]="#website .btn-primary{background-color:_COLOR_;border-color:_COLOR_}";
sColorable["primaryButtonHover"]="#website .btn-primary:hover{background-color:_COLOR_;border-color:_COLOR_}";
sColorable["contentLink"]=".WxEditableArea a:not(.btn){color:_COLOR_}";
sColorable["contentLinkHover"]=".WxEditableArea a:not(.btn):hover{color:_COLOR_}";
sColorable["websiteTitleOnWhiteTopThemes"]=".koTheme2 .logoHolder a,.koTheme3 .logoHolder a,.koTheme4 .logoHolder a{color:_COLOR_}";

sColorable["koColor"]="#contentArea h1.koColor,#contentArea h2.koColor,#contentArea h3.koColor,#contentArea h4.koColor{color:_COLOR_}ul.koCheckList li:before {background:_COLOR_}.koThemeDark ul.koCheckList li:before,.keditDark ul.koCheckList li:before {color:_COLOR_}.koColorIcon i{color:_COLOR_}";
sColorable["koColorDark"]="#contentArea .keditDark h1.koColor,#contentArea .keditDark h2.koColor,#contentArea .keditDark h3.koColor,#contentArea .keditDark h4.koColor,.koThemeDark #contentArea h1.koColor,.koThemeDark #contentArea h2.koColor,.koThemeDark #contentArea h3.koColor,.koThemeDark #contentArea h4.koColor{color:_COLOR_}.koThemeDark ul.koCheckList li:before,.keditDark ul.koCheckList li:before {background:_COLOR_}.koThemeDark .koColorIcon i,.keditDark .koColorIcon i{color:_COLOR_}";

sColorable["colorSet1"] = ".colorSet1{background-color:_COLOR_}.colorSet5{color:_COLOR_}.colorBorder1{border-color:_COLOR_}";
sColorable["colorSet2"] = ".colorSet2{background-color:_COLOR_}.colorBorder2{border-color:_COLOR_}";
sColorable["colorSet3"] = ".colorSet3{background-color:_COLOR_}.colorBorder3{border-color:_COLOR_}";
sColorable["colorSet4"] = ".colorSet3{background-color:_COLOR_}.colorBorder4{border-color:_COLOR_}";
sColorable["colorSet5"] = ".colorSet5{background-color:_COLOR_}.colorSet1,.colorSet2,.colorSet3{color:_COLOR_}.colorBorder5{border-color:_COLOR_}";

var colorScheme=new Array();colorScheme[1]=new Array("#004987","#006cc9","#008df2","#7ac8ff","#ccecff");colorScheme[2]=new Array("#006b0e","#0f9400","#79ba25","#bee78d","#eaf7da");colorScheme[3]=new Array("#d74300","#f07500","#ffa600","#ffe180","#fff7c2");colorScheme[4]=new Array("#9e0013","#d50019","#f05258","#ffabad","#ffd9da");colorScheme[5]=new Array("#7a0222","#9e032e","#cc1c4d","#eba4b8","#f5d2db");colorScheme[6]=new Array("#73040b","#9e030b","#ce2029","#eda4a7","#facdd0");colorScheme[7]=new Array("#4a090e","#6e060d","#94121c","#d4a0a4","#ead0d2");colorScheme[8]=new Array("#611000","#891700","#a32a00","#e3ad99","#f4ded6");colorScheme[9]=new Array("#80170e","#ad2416","#e34234","#cca29f","#e6d1cf");colorScheme[10]=new Array("#801300","#bf1d00","#ff2600","#ffa899","#ffd4cc");colorScheme[11]=new Array("#80001c","#bf002a","#ff0038","#e599aa","#f2ccd4");colorScheme[12]=new Array("#802700","#c72b00","#ff4e00","#f0b092","#fadaca");colorScheme[13]=new Array("#802200","#b53300","#e85c12","#f6bea0","#faded0");colorScheme[14]=new Array("#b13e1e","#ec551e","#ff7518","#ffc8a3","#ffe3d1");colorScheme[15]=new Array("#aa3e00","#f26c0d","#ff8f00","#ffcf99","#ffecd6");colorScheme[16]=new Array("#c75000","#f27500","#ff9f00","#ffd999","#ffeccc");colorScheme[17]=new Array("#993b17","#f06937","#ff8249","#ffcdb6","#ffe6db");colorScheme[18]=new Array("#823113","#b55632","#d06a3e","#ecc3b2","#f7e7e0");colorScheme[19]=new Array("#553000","#713a00","#964b00","#d5b799","#eadbcc");colorScheme[20]=new Array("#4a140b","#521c13","#703422","#ccaba1","#ebdfd9");colorScheme[21]=new Array("#461a09","#69270d","#8c3611","#d1afa0","#e8d7cf");colorScheme[22]=new Array("#361305","#4f1a03","#633826","#c1afa8","#e0d7d4");colorScheme[23]=new Array("#402200","#593000","#73420e","#c7b39f","#e3d9cf");colorScheme[24]=new Array("#4b3321","#704d32","#956642","#d5c2b3","#eae0d9");colorScheme[25]=new Array("#73502e","#996a3d","#c18e60","#e6d2bf","#f3e8df");colorScheme[26]=new Array("#693906","#a65c11","#d27f29","#edcca9","#f6e5d4");colorScheme[27]=new Array("#9b4200","#ed7710","#ffb300","#fce19b","#fef3d7");colorScheme[28]=new Array("#b67900","#ffa400","#ffd900","#fff099","#fff9d6");colorScheme[29]=new Array("#e68e15","#f5a71c","#ffca1d","#ffeaa5","#fff4d2");colorScheme[30]=new Array("#e3ac00","#ffd400","#fff300","#fffa99","#fffdcc");colorScheme[31]=new Array("#8f9601","#b5bf07","#d4de1b","#eef2a4","#f6f8d1");colorScheme[32]=new Array("#7fb900","#9fde23","#c1f900","#cbfa87","#eafdcf");colorScheme[33]=new Array("#5d8005","#7ba60d","#9cc925","#d7e9a8","#ebf4d3");colorScheme[34]=new Array("#3e5404","#57730e","#6c8b1b","#c4d1a4","#e2e8d1");colorScheme[35]=new Array("#2f3600","#4a5200","#697800","#d6dfb2","#e4eacb");colorScheme[36]=new Array("#26290f","#393e16","#515918","#b7baa5","#dbdcd2");colorScheme[37]=new Array("#00570a","#377d00","#5ba825","#c2f79e","#ebfcdf");colorScheme[38]=new Array("#255c00","#378900","#49b700","#b6e299","#dbf1cc");colorScheme[39]=new Array("#0e440e","#156615","#1c881c","#a4cfa4","#d2e7d2");colorScheme[40]=new Array("#004420","#005b2a","#007e3e","#99cbb2","#cce5d8");colorScheme[41]=new Array("#004c3b","#007259","#009876","#99d6c8","#cceae4");colorScheme[42]=new Array("#005b5a","#009488","#00b3a2","#99e1da","#d6f3f0");colorScheme[43]=new Array("#004d5b","#006779","#338594","#adced4","#deebee");colorScheme[44]=new Array("#004b5c","#007089","#0095b7","#99d5e2","#cceaf1");colorScheme[45]=new Array("#006d90","#0090bf","#00aeef","#99dff9","#cceffc");colorScheme[46]=new Array("#0079a8","#00a2d9","#21d1f7","#a6edfc","#d3f6fd");colorScheme[47]=new Array("#71c2eb","#a8e3ff","#dbf3ff","#f1faff","#f8fdff");colorScheme[48]=new Array("#0b5794","#136db5","#3794dd","#afd4f1","#d7eaf8");colorScheme[49]=new Array("#003b71","#004f96","#0064bf","#99c1e5","#cce0f2");colorScheme[50]=new Array("#154b70","#246594","#3983b6","#b0cde2","#d7e6f0");colorScheme[51]=new Array("#004b8f","#0064bf","#0085ff","#99ceff","#cce7ff");colorScheme[52]=new Array("#143872","#1e54aa","#2870e3","#a9c6f4","#d4e2f9");colorScheme[53]=new Array("#00163e","#00205d","#003494","#99aacb","#ccd5e5");colorScheme[54]=new Array("#270059","#33036e","#481884","#b6a3ce","#dad1e6");colorScheme[55]=new Array("#3e144e","#5d1d74","#7c279b","#cba9d7","#e5d4eb");colorScheme[56]=new Array("#8f0758","#ab156d","#ce3c92","#ebb1d3","#f5d8e9");colorScheme[57]=new Array("#9c546f","#cc7695","#ffaac9","#ffdde9","#ffeef4");colorScheme[58]=new Array("#a65059","#d9737f","#ff95a3","#ffd5da","#ffeaed");colorScheme[59]=new Array("#a11d47","#c43b67","#fb5589","#fdbbd0","#fedde7");colorScheme[60]=new Array("#801848","#bf246b","#ff308f","#ffacd2","#ffd6e9");colorScheme[61]=new Array("#86192f","#b3213e","#e33e61","#f4b2c0","#f9d8df");colorScheme[62]=new Array("#000000","#333333","#666666","#999999","#CCCCCC","#FFFFFF");