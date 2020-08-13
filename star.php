
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>Star Ratings</title>
<style>
	/*	.ratingnew {
		  unicode-bidi: bidi-override;
		  direction: rtl;
		  text-align: center;
		}
		.ratingnew > span {
		  display: inline-block;
		  position: relative;
		  width: 1.1em; font-size:22px;
		}
		.ratingnew > span:hover,
		.ratingnew > span:hover ~ span {
		  color: transparent;
		}
		.ratingnew > span:hover:before,
		.ratingnew > span:hover ~ span:before {
		   content: "\2605";
		   position: absolute;
		   left: 0;
		   color: gold;
		}*/
		.rating-stars {
    width: 99px;
    height: 20px;
    background: url(spr_icons-1.svg) no-repeat;
    background-size: 100%;
}

.rating-stars.rating_0 {
    background-position: 0 -113px;
}
.rating-stars.rating_1 {
    background-position: 0 -75px;
}
.rating-stars.rating_2 {
    background-position: 0 -57px;
}
.rating-stars.rating_3 {
    background-position: 0 -39px;
}
.rating-stars.rating_4 {
    background-position: 0 -20px;
}
.rating-stars.rating_5 {
    background-position: 0 -1px;
}
	</style>
</head>
<body>

<!--<div class="ratingnew">
<span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
</div>-->
<div class="rating-wrap">
 <div class="rating-stars rating_0"></div>
 </div>
 
<div class="rating-wrap">
 <div class="rating-stars rating_1"></div>
 </div>
 
 <div class="rating-wrap">
 <div class="rating-stars rating_2"></div>
 </div>
 
 <div class="rating-wrap">
 <div class="rating-stars rating_3"></div>
 </div>
 
 <div class="rating-wrap">
 <div class="rating-stars rating_4"></div>
 </div>
 <div class="rating-wrap">
 <div class="rating-stars rating_5"></div>
 </div>
</body>
</html>