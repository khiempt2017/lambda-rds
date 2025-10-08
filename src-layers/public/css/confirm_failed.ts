export const confirmFailedCSS = `
@charset "UTF-8";

html {
  font-family: Arial, Roboto, “Droid Sans”, “游ゴシック”, YuGothic, “ヒラギノ角ゴ ProN W3”, “Hiragino Kaku Gothic ProN”, “メイリオ”, Meiryo, sans-serif;
  -ms-text-size-adjust: 100%;
  -webkit-text-size-adjust: 100%;
}

body {
  font-family: Arial, Roboto, “Droid Sans”, “游ゴシック”, YuGothic, “ヒラギノ角ゴ ProN W3”, “Hiragino Kaku Gothic ProN”, “メイリオ”, Meiryo, sans-serif;
  margin:0;
  padding:0;
}

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
main,
menu,
nav,
section,
summary {
  display: block;
}

audio,
canvas,
progress,
video {
  display: inline-block;
  vertical-align: baseline;
}

audio:not([controls]) {
  display: none;
  height: 0;
}

[hidden],
template {
  display: none;
}

a {
  background-color: transparent;
}

a:active,
a:hover {
  outline: 0;
}

abbr[title] {
  border-bottom: 1px dotted;
}

b,
strong {
  font-weight: bold;
}

dfn {
  font-style: italic;
}

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

mark {
  background: #ff0;
  color: #000;
}

small {
  font-size: 80%;
}

sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sup {
  top: -0.5em;
}

sub {
  bottom: -0.25em;
}

img {
  border: 0;
}

svg:not(:root) {
  overflow: hidden;
}

figure {
  margin: 1em 40px;
}

hr {
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  height: 0;
}

pre {
  overflow: auto;
}

code,
kbd,
pre,
samp {
  font-size: 1em;
}

button,
input,
optgroup,
select,
textarea {
  color: inherit;
  font: inherit;
  margin: 0;
}

button {
  overflow: visible;
}

button,
select {
  text-transform: none;
}

button,
html input[type="button"],
input[type="reset"],
input[type="submit"] {
  -webkit-appearance: button;
  cursor: pointer;
}

button[disabled],
html input[disabled] {
  cursor: default;
}

button::-moz-focus-inner,
input::-moz-focus-inner {
  border: 0;
  padding: 0;
}

input {
  line-height: normal;
}

input[type="checkbox"],
input[type="radio"] {
  box-sizing: border-box;
  padding: 0;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

input[type="search"] {
  -webkit-appearance: textfield;
  -moz-box-sizing: content-box;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
}

input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

fieldset {
  border: 1px solid #c0c0c0;
  margin: 0 2px;
  padding: 0.35em 0.625em 0.75em;
}

legend {
  border: 0;
  padding: 0;
}

textarea {
  overflow: auto;
}

optgroup {
  font-weight: bold;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}

td,
th {
    padding: 0;
}

body {
    font-size: 16px;
    line-height: 1em;
}

h1,
h2,
h3,
h4,
h5,
h6,
p {
    margin: 0;
    padding: 0;
}

ul {
    margin: 0;
    padding: 0;
}

ul li {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

img{
    vertical-align: top;
}
a{
    color: #00A9E7;
}
#head{
    padding: 20px 14px;
    border-bottom: solid 1px #ccc;
}
#head h1{
    padding: 0;
}
#head img{
    height: 15px;
    width: auto;
    vertical-align: baseline;
}
#mv{
    padding: 22px 15px 0;
    text-align: center;
}

#mv h2{
    font-size: 16px;
    line-height: 1.8em;
    font-weight: bold;
    color: #5C687F;
    text-align: center;
    margin: 40px 0 40px;
}

#mv p{
    font-size: 14px;
    line-height: 1.8em;
    font-weight:normal;
    color: #5C687F;
    text-align: left;
    margin: 0 15px 220px 15px;
}

#mv img{
    max-width: 270px;
    width: 100%;
}
#main{
    padding: 0 25px 75px;
    background: #F8F8F8;
    text-align: center;
}

#pic{
    padding: 40px 0 14px;
}
#pic img{
    max-width: 460px;
    width: 100%;
}
#pic2{
    padding: 14px 0 50px;
}
#pic2 img{
    max-width: 588px;
    width: 100%;
}
#lead{
    text-align: left;
    font-size: 13px;
    line-height: 1.7em;
    padding: 0 0 30px;
    color: #5C687F;
}
#more_link{
    font-size: 13px;
    line-height: 1em;
}
#more_link a{
    text-decoration: none;
}

#foot_link ul{
    display: block;
    text-align: center;
    padding: 20px 0;
}
#foot_link ul li{
    display: inline-block;
    font-size: 12px;
    line-height: 1em;
    border-right: solid 1px #7e7e7e;
}
#foot_link ul li:last-child{
    border: none;
}
#foot_link ul li a{
    padding: 0 15px;
    text-decoration: underline;
}

#foot #copyright{
    background: #666666;
    color: #dddddd;
    text-align: center;
    padding: 30px 0;
    font-size: 11px;
    line-height: 16px;
}

.lay {
    max-width: 960px;
    width: 100%;
    margin: 0 auto;
}

.pc{
    display: none;
}

@media only screen and (min-width: 768px) {
    #head{
        padding: 20px 15px;
        position: fixed;
        width: 100%;
        background-color: #FFF;
        z-index: 100;
    }
    #head h1{
        padding: 0 5px;
    }
    #head img{
        height: 21px;
        width: auto;
        vertical-align: top;
    }
    #foot #copyright{
        font-size: 12px;
    }
    #mv{
        padding-top: 130px;
    }
    #mv h2{
        font-size: 20px;
        margin: 0 0 70px;
    }
    
    #mv p{
 
        text-align: center;
        margin: 0 15px 30px 15px;
    }
    
    
    #pic{
        padding: 60px 0 60px;
    }
    #lead{
        max-width: 588px;
        margin: 0 auto;
        font-size: 14px;
        padding: 0 0 50px;
    }
    #main{
        padding: 0 25px 100px;
    }
    
    #foot #copyright{
        padding: 13px 0;
        font-size: 12px;
        line-height: 1em;
        color: #fff;
    }
    .pc{
        display: inline;
    }
    .sp{
        display: none;
    }
}
`;
