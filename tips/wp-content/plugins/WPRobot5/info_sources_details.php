<?php
/*** INFO ARRAY ***/
	$wpr5_source_infos2 = array(	
		"sources" => array(
			"amazon" => array(
				"request" => 'http://webservices.amazon.{region}/onca/xml',
				"limits" => array("request" => 10, "total" => 100),		
				"title" => "Title",		
				"unique" => "ASIN",
				"error" => "Error",
				"level1" => "Items",			
				"selector" => "node",	
				"price" => "FormattedPrice",			
				"url" => "DetailPageURL",				
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html"
			),
			"articlebuilder" => array(
				"request" => 'http://articlebuilder.net/api.php',
				"limits" => array("request" => 1, "total" => 10),		
				"title" => "title",				
				"error" => "error",					
				"selector" => "item",			
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "http://paydotcom.net/r/114431/thoefter/26922760/",
				"paid" => 1
			),	
			"articles" => array(
				"limits" => array("request" => 20, "total" => 9999),		
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "no"			
			),		
			"avantlink" => array(
				//"request" => 'https://www.avantlink.com/api.php?module=ProductSearch&affiliate_id={appid}&search_term={keyword}&website_id={websiteid}&search_results_base={start}&search_results_count={num}&search_price_maximum={highprice}&search_price_minimum={lowprice}&merchant_ids={advertisers}',
				"request" => 'https://classic.avantlink.com/api.php?module=ProductSearch&affiliate_id={appid}&search_term={keyword}&website_id={websiteid}&search_results_base={start}&search_results_count={num}&search_price_maximum={highprice}&search_price_minimum={lowprice}&merchant_ids={advertisers}',
				"title" => "Product_Name",		
				"unique" => "Product_Id",
				"error" => "error",	
				"selector" => "Table1",		
				"categories" => array("shopping"), 
				"price" => "Sale_Price",			
				"url" => "Buy_URL",					
				"icon" => "", 
				"signup" => "http://www.avantlink.com/"
			),	
			"bestbuy" => array(
				"request" => 'http://api.bestbuy.com/v1/products(search={keyword}&manufacturer={manufacturer})?apiKey={appid}&page={start}&pageSize={num}',
				"title" => "name",		
				"unique" => "productId",
				"error" => "h1",	
				"selector" => "product",		
				"categories" => array("shopping"), 
				"price" => "salePrice",			
				"url" => "url",						
				"icon" => "", 
				"signup" => "https://developer.bestbuy.com/"
			),				
			"bigcontentsearch" => array(
				"request" => 'https://members.bigcontentsearch.com/api/articles_get_by_search_term?username={username}&api_key={api_key}&search_term={keyword}&count={num}',
				"limits" => array("request" => 30, "total" => 500),			
				"title" => "title",		
				"unique" => "uid",
				"error" => "error_msg2",
				"selector" => "node",		
				"level1" => "response",
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "http://wprobot.net/go/bigcontentsearch",
				"json" => 1,
				"paid" => 1
			),
			"commissionjunction" => array(
				"request" => 'https://product-search.api.cj.com/v2/product-search?website-id={websiteid}&advertiser-ids={advertisers}&keywords={keyword}&low-price={lowprice}&high-price={highprice}&sort-by={sort}&sort-order={sortorder}&page-number={start}&records-per-page={num}',
				"title" => "name",		
				"unique" => "sku",
				"error" => "error-message",
				"level1" => "products",
				"selector" => "product",
				"price" => "price",			
				"url" => "buy-url",					
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://www.cj.com/webservices"
			),
			"datafeed" => array(
				"categories" => array("shopping","content"), 
				"icon" => "", 
				"signup" => "no"
			),	
			"ebay" => array(
				"request" => 'notrequired',
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "https://www.ebaypartnernetwork.com/files/hub/en-US/index.html"
			),
			"etsy" => array(
				"request" => 'https://openapi.etsy.com/v2/listings/active?region={region}&api_key={appid}&limit={num}&offset={start}&keywords={keyword}&min_price={minprice}&max_price={maxprice}&sort_on={order}&includes=MainImage,Images,Shop',
				"limits" => array("request" => 50, "total" => 100000),	
				"title" => "title",		
				"unique" => "listing_id",
				"error" => "error",	
				"level1" => "results",
				"json" => 1,	
				"selector" => "results/node",	
				"price" => "price",			
				"url" => "url",					
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://etsy.com"
			),			
			"eventful" => array(
				"request" => 'http://api.eventful.com/rest/events/search?app_key={appid}&keywords={keyword}&category={cat}&location={location}&sort_order={sort}&page_size={num}&page_number={start}&include=price',
				"title" => "title",		
				"unique" => "id",
				"error" => "description",	
				"level1" => "events",			
				"selector" => "event",			
				"url" => "url",					
				"categories" => array("content","regional"), 
				"icon" => "", 
				"signup" => "http://api.eventful.com/"
			),
			/*"expedia" => array(
				"request" => 'http://api.ean.com/ean-services/rs/hotel/v3/list?minorRev=14&cid={affid}&apiKey={appid}&customerUserAgent=&customerIpAddress=&locale=en_US&sort={sort}&currencyCode=USD&_type=xml&xml=<HotelListRequest><destinationString>{keyword}</destinationString><numberOfResults>{num}</numberOfResults><supplierCacheTolerance>MED_ENHANCED</supplierCacheTolerance></HotelListRequest>',		
				"title" => "name",		
				"unique" => "hotelId",	
				"error" => "error",
				"level1" => "HotelList",			
				"selector" => "HotelSummary",	
				"categories" => array("content","shopping"), 
				"icon" => "", 
				"signup" => "http://developer.ean.com/"
			),	*/		
			"flickr" => array(
				"request" => 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key={appid}&text={keyword}&sort={sort}&content_type={cont}&license={license}&extras=date_taken%2C+owner_name%2C+icon_server%2C+geo%2C+tags%2C+machine_tags%2C+media%2C+path_alias%2C+url_sq%2C+url_t%2C+url_s%2C+url_m%2C+url_l%2C+url_o%2C+description&per_page={num}&page={start}',
				"title" => "title",		
				"unique" => "id",	
				"error" => "err",
				"level1" => "photos",
				"selector" => "photo",		
				"categories" => array("media","comments"), 
				"icon" => "", 
				"signup" => "http://www.flickr.com/services/"
			),
			"aliexpress" => array(
				"request" => 'http://gw.api.alibaba.com/openapi/param2/2/portals.open/api.listPromotionProduct/{appid}?fields=productId,productTitle,productUrl,imageUrl,originalPrice,salePrice,discount&keywords={keyword}&pageSize={num}&pageNo={start}&originalPriceTo={maxprice}&originalPriceFrom={minprice}&highQualityItems={resultqual}&sort={order}&categoryId={categoryId}',
				"title" => "productTitle",	
				"limits" => array("request" => 40, "total" => 999999),				
				"unique" => "productId",
				"selector" => "node",
				"json" => 1,	
				"url" => "productUrl",	
				"price" => "salePrice",
				"categories" => array("shopping"), 
				"icon" => "",
				"signup" => "https://portals.aliexpress.com/help/help_center_API.html"
			),				
			"bingnews" => array(
				"request" => 'https://api.cognitive.microsoft.com/bing/v5.0/news/search?q={keyword}&count={num}&offset={start}&mkt={lang}&safeSearch=Moderate',
				"title" => "name",		
				"unique" => "url",
				"selector" => "value",
				"json" => 1,	
				"url" => "url",					
				"categories" => array("content"), 
				"icon" => "",
				"signup" => "https://www.microsoft.com/cognitive-services/"
			),			
			"indeed" => array(
				"request" => 'http://api.indeed.com/ads/apisearch?publisher={appid}&q={keyword}&v=2&l={location}&sort={sort}&radius={radius}&st=&jt={jobtype}&start={start}&limit={num}&fromage={age}&filter=0&latlong=0&co={country}&chnl=&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29',
				"title" => "jobtitle",		
				"unique" => "jobkey",	
				"error" => "error",
				"level1" => "results",
				"url" => "url",						
				"selector" => "result",		
				"categories" => array("content","regional"), 
				"icon" => "", 
				"signup" => "http://www.indeed.com/jsp/apiinfo.jsp"
			),
			"itunes" => array(
				"request" => 'http://ax.phobos.apple.com.edgesuite.net/WebObjects/MZStoreServices.woa/wa/wsSearch?term={keyword}&country={country}&media={media}&limit={num}&lang={lang}',
				"limits" => array("request" => 200, "total" => 200),
				"title" => "trackName",		
				"unique" => "trackId",	
				"error" => "Message",
				"level1" => "results",
				"selector" => "node",	
				"json" => 1,			
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "no"
			),		
			"linkshare" => array(
				"request" => 'http://productsearch.linksynergy.com/productsearch?token={appid}&keyword="{keyword}"&MaxResults={num}&pagenumber={start}&cat=&sort={sort}&sorttype=asc&merchant={merchant}',
				"title" => "productname",		
				"unique" => "sku",
				"error" => "Errors",
				"selector" => "item",
				"price" => "price",			
				"url" => "linkurl",					
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://www.linkshare.com/",			
			),
			"oodle" => array(
				"request" => 'http://api.oodle.com/api/v2/listings?key={appid}&region={lang}&q={keyword}&location={location}&radius={radius}&sort=ctime&start={start}&num={num}&category={cat}',
				"title" => "title",		
				"unique_direct" => "id",
				"error" => "error",
				"level1" => "listings",			
				"selector" => "element",	
				"url" => "url",					
				"categories" => array("content","regional"), 
				"icon" => "", 
				"signup" => "http://developer.oodle.com/oodle-api"
			),
			"pixabay" => array(
				"request" => 'https://pixabay.com/api/?key=281886-58afb50cd9c4019517ce11b78&search_term={keyword}&image_type={image_type}&response_group=high_resolution&per_page={num}&page={start}',			
				"limits" => array("request" => 100, "total" => 1000),		
				"title" => "type",		
				"unique" => "id_hash",	
				"error" => "error",
				"level1" => "hits",
				"json" => 1,
				"selector" => "node",		
				"categories" => array("media"), 
				"icon" => "", 				
			),				
			"prosperent" => array(
				"request" => 'http://api.prosperent.com/api/search?api_key={appid}&query={keyword}&visitor_ip=192.168.0.1&page={start}&limit={num}&filterMerchant={merchant}&minPrice={minprice}&maxPrice={maxprice}&sortPrice=DESC&imageSize={imagesize}',
				"limits" => array("request" => 100, "total" => 500),
				"title" => "keyword",		
				"unique" => "productId",	
				"error" => "errorsXXX",
				"selector" => "node",	
				"price" => "price",			
				"url" => "affiliate_url",					
				"level1" => "data",				
				"json" => 1,			
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://prosperent.com/"
			),			
			"recipepuppy" => array(
				"request" => 'http://www.recipepuppy.com/api/?i=&q={keyword}&p={start}&format=xml',
				"title" => "title",		
				"unique" => "",
				"error" => "error",	
				"selector" => "recipe",		
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "no"
			),	
			"rottentomatoes" => array(
				"request" => 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey={appid}&q={keyword}&page_limit={num}&page={start}',
				"limits" => array("request" => 30, "total" => 100),
				"title" => "title",		
				"unique" => "title",	
				"error" => "errors",
				"selector" => "node",	
				"level1" => "movies",				
				"json" => 1,			
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "http://developer.rottentomatoes.com/"
			),			
			"rss" => array(
				"title" => "title",		
				"unique" => "guid",	
				"error" => "error",	
				"level1" => "channel",				
				"selector" => "item",
				"url" => "link",					
				"categories" => array("content"), 
				"icon" => "", 
				"signup" => "no"
			),					
			"spinchimp" => array(
				"categories" => array("rewriting"), 
				"icon" => "", 
				"signup" => "http://542c4iv7ejm1cq6-snxenhk6kz.hop.clickbank.net/",
				"paid" => 1
			),	
			"spinnerchief" => array(
				"categories" => array("rewriting"), 
				"icon" => "", 
				"signup" => "http://paydotcom.net/r/108731/thoefter/26893269/",
				"paid" => 1
			),
			"spinrewriter" => array(
				"categories" => array("rewriting"), 
				"icon" => "", 
				"signup" => "http://www.spinrewriter.com/?ref=6967",
				"paid" => 1
			),			
			"shopzilla" => array(
				"request" => 'http://catalog.bizrate.com/services/catalog/v1/us/product?apiKey={appid}&publisherId={pubid}&keyword={keyword}&start={start}&results={num}&resultsOffers={offers}&sort={sort}&minPrice={lowprice}&maxPrice={highprice}&biddedOnly=true',
				"title" => "title",		
				"unique" => "productId",	
				"error" => "Message",	
				"level1" => "Products",
				"selector" => "Product",		
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "https://publisher.shopzilla.com/reg_page1.xhtml"
			),
			"skimlinks" => array(
				"request" => 'http://api-products.skimlinks.com/query?q={keyword}&version=3&key={appid}&format=xml&start={start}&rows={num}',
				"title" => "title",		
				"unique" => "id",	
				"error" => "error",	
				"price" => "price",			
				"url" => "url",					
				"selector" => "products",		
				"categories" => array("shopping"), 
				"limits" => array("request" => 300, "total" => 1000),	
				"icon" => "", 
				"signup" => "http://api-products.skimlinks.com"
			),		
			"thebestspinner" => array(
				"categories" => array("rewriting"), 
				"icon" => "", 
				"signup" => "http://paydotcom.net/r/96144/thoefter/26522731/",
				"paid" => 1
			),	
			"tradedoubler" => array(
				"request" => 'https://api.tradedoubler.com/1.0/products;pretty=true;minPrice={minprice};maxPrice={maxprice};pageSize={num};page={start};limit=999999;language={language};orderBy={order};priceHistory=true;q={keyword}?token={appid}',
				"limits" => array("request" => 10000, "total" => 100000),	
				"title" => "name",		
				"unique" => "ean",
				"error" => "error",	
				"level1" => "products",
				"price" => "price",			
				"url" => "productUrl",						
				"json" => 1,	
				"selector" => "products/node",	
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://tradedoubler.com"
			),			
			"vimeo" => array(
				"request" => 'http://vimeo.com/api/rest/v2?method=vimeo.videos.search&full_response=1&page={start}&per_page={num}&query={keyword}&sort={sort}',
				"limits" => array("request" => 50, "total" => 100000),	
				"title" => "title",		
				"unique" => "id",
				"error" => "err",	
				"level1" => "videos",
				"selector" => "video",	
				"url" => "videosurl",					
				"oauth" => 1,
				"categories" => array("media"), 
				"icon" => "", 
				"signup" => "http://vimeo.com/api"
			),	
			"walmart" => array(
				"request" => 'http://api.walmartlabs.com/v1/search?apiKey={appid}&lsPublisherId={lsid}&format=xml&query={keyword}&sort=bestseller&responseGroup=full&start={start}&numItems={num}',
				"title" => "name",		
				"unique" => "itemId",
				"error" => "h1",	
				"selector" => "items",		
				"categories" => array("shopping"), 
				"price" => "salePrice",			
				"url" => "productUrl",						
				"icon" => "", 
				"signup" => "https://developer.walmartlabs.com"
			),				
			"wordai" => array(
				"categories" => array("rewriting"), 
				"icon" => "", 
				"signup" => "http://wprobot.net/go/wordai",
				"paid" => 1
			),			
			"yahooanswers" => array(
				"request" => 'http://answers.yahooapis.com/AnswersService/V1/questionSearch?region={region}&appid={appid}&query={keyword}&type=resolved&start={start}&results={num}',
				"limits" => array("request" => 50, "total" => 1000),	
				"title" => "Subject",		
				"unique" => "id",
				"error" => "Message",	
				"selector" => "Question",			
				"categories" => array("content","comments"), 
				"icon" => "", 
				"signup" => "http://developer.yahoo.com/answers/"
			),
			"yelp" => array(
				//"request" => 'http://api.yelp.com/business_review_search?term={keyword}&location={location}&ywsid={appid}&cc={region}&radius={radius}',
				"request" => 'https://api.yelp.com/v2/search?term={keyword}&location={location}&limit={num}&offset={start}&radius_filter={radius}&oauth_consumer_key={appid}',
				"title" => "name",		
				"unique" => "id",	
				"oauth" => 1,
				"json" => 1,	
				"url" => "url",					
				"level1" => "businesses",			
				"selector" => "businesses/node",		
				"categories" => array("content","regional"), 
				"icon" => "", 
				"signup" => "http://www.yelp.com/developers/documentation/v2/overview"
			),		
			"youtube" => array(
				//"request" => 'http://gdata.youtube.com/feeds/api/videos?q={keyword}&orderby={sort}&start-index={start}&max-results={num}&format=5&safeSearch={safesearch}&lr={lang}&v=2',
				"request" => 'https://www.googleapis.com/youtube/v3/search?key={appid}&part=snippet&q={keyword}&order={sort}&maxResults={num}&safeSearch={safesearch}&relevanceLanguage={lang}&pageToken={next}&channelId={channel}&type=video', // {num}
				"title" => "title",
				"unique" => "videoId",
				//"oauth" => 1,
				"json" => 1,	
				"error" => "error",		
				"level1" => "items",	
				"selector" => "node",	
				"categories" => array("media","comments"), 
				"icon" => "", 
				"signup" => "no"
			),	
			"zanox" => array(
				"request" => 'http://api.zanox.com/xml/2011-03-01/products?region={region}&q={keyword}&partnership={partnership}&connectid={appid}&page={start}&items={num}&hasimages=true&programs={programs}&minprice={minprice}&maxprice={maxprice}',
				"limits" => array("request" => 50, "total" => 100000),	
				"title" => "name",		
				"unique" => "merchantProductId",
				"error" => "error",	
				"level1" => "productItems",
				"url" => "ppc",	
				"price" => "price",					
				"selector" => "productItem",	
				"categories" => array("shopping"), 
				"icon" => "", 
				"signup" => "http://zanox.com"
			),		
		),		
	);

$wpr5_source_infos = array(
	"categories" => array(
		"rewriting" => array(
			"name" => "Rewriting and Translation",
			"description" => "",
			"sources" => array("spinnerchief", "spinchimp", "spinrewriter", "thebestspinner", "wordai")
		),
		"comments" => array(
			"name" => "Content with Comments / Answers",
			"description" => "",
			"sources" => array("flickr", "youtube")
		),	
		"shopping" => array(
			"name" => "Shopping, Products and Affiliate Networks",
			"description" => "",
			"sources" => array("aliexpress", "amazon", "avantlink", "bestbuy", "commissionjunction", "datafeed", "ebay", "etsy", "expedia", "linkshare", "shopzilla", "skimlinks", "tradedoubler", "walmart", "zanox")
		),	
		"content" => array(
			"name" => "Content",
			"description" => "",
			"sources" => array("articles", "datafeed", "eventful", "expedia", "indeed", "itunes", "oodle", "rottentomatoes", "rss", "yelp")
		),
		"media" => array(
			"name" => "Pictures and Videos",
			"description" => "",
			"sources" => array("flickr", "pixabay", "youtube", "vimeo")
		),
		"regional" => array(
			"name" => "Regional Targeted Content",
			"description" => "",
			"sources" => array("eventful", "indeed", "oodle", "yelp")
		),					
	),
	"languages" => array(
		"de" => array(
			"name" => "German",
			"icon" => "",
			"sources" => array("amazon", "commissionjunction", "datafeed", "ebay", "shopzilla", "youtube" )
		),	
		"es" => array(
			"name" => "Spanish",
			"icon" => "",
			"sources" => array("amazon", "ebay", "youtube", "datafeed")
		),	
		"fr" => array(
			"name" => "French",
			"icon" => "",
			"sources" => array("amazon", "datafeed", "ebay", "shopzilla", "youtube" )
		),			
		"it" => array(
			"name" => "Italian",
			"icon" => "",
			"sources" => array("amazon", "datafeed", "ebay", "youtube" )
		),	
		"pt" => array(
			"name" => "Portuguese",
			"icon" => "",
			"sources" => array("youtube", "datafeed")
		),	
		"nl" => array(
			"name" => "Dutch",
			"icon" => "",
			"sources" => array("youtube", "ebay", "datafeed")
		),			
		"ru" => array(
			"name" => "Russian",
			"icon" => "",
			"sources" => array("datafeed", "youtube" )
		),			
		"jp" => array(
			"name" => "Japanese",
			"icon" => "",
			"sources" => array("amazon", "datafeed", "itunes", "youtube" )
		),	
		"zn" => array(
			"name" => "Chinese",
			"icon" => "",
			"sources" => array("datafeed", "ebay", "youtube" )
		),				
	),		
	"sources" => array(
		"aliexpress" => array(
			"name" => "AliExpress",		
			"limits" => array("request" => 40, "total" => 100),		
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://portals.aliexpress.com/help/help_center_API.html"
		),
		"amazon" => array(
			"name" => "Amazon",		
			"limits" => array("request" => 10, "total" => 100),		
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://affiliate-program.amazon.com/gp/advertising/api/detail/main.html"
		),
		"articlebuilder" => array(
			"name" => "Article Builder",			
			"limits" => array("request" => 1, "total" => 10),			
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "http://paydotcom.net/r/114431/thoefter/26922760/",
			"paid" => 1
		),	
		"articles" => array(
			"name" => "Articles",			
			"limits" => array("request" => 20, "total" => 9999),		
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "no"			
		),		
		"avantlink" => array(
			"name" => "Avantlink",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://www.avantlink.com/"
		),	
		"bestbuy" => array(
			"name" => "BestBuy",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://developer.bestbuy.com/"
		),			
		"bigcontentsearch" => array(
			"name" => "Big Content Search",			
			"limits" => array("request" => 30, "total" => 500),			
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "http://wprobot.net/go/bigcontentsearch",
			"paid" => 1
		),
		"bingnews" => array(
			"name" => "Bing News",			
			"categories" => array("content"), 
			"icon" => "",
			"signup" => "https://www.microsoft.com/cognitive-services/"
		),		
		"commissionjunction" => array(
			"name" => "Commission Junction",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://api.cj.com/sign_up.cj"
		),
		"datafeed" => array(
			"name" => "CSV Datafeeds",			
			"categories" => array("shopping","content"), 
			"icon" => "", 
			"signup" => "no"
		),	
		"ebay" => array(	
			"name" => "eBay",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://www.ebaypartnernetwork.com/files/hub/en-US/index.html"
		),
		"etsy" => array(
			"name" => "Etsy",			
			"limits" => array("request" => 50, "total" => 100000),	
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://etsy.com"
		),			
		"eventful" => array(
			"name" => "Eventful",			
			"categories" => array("content","regional"), 
			"icon" => "", 
			"signup" => "http://api.eventful.com/"
		),
		/*"expedia" => array(
			"name" => "Expedia",			
			"categories" => array("content","shopping"), 
			"icon" => "", 
			"signup" => "http://developer.ean.com/"
		),*/			
		"flickr" => array(
			"name" => "Flickr",			
			"categories" => array("media","comments"), 
			"icon" => "", 
			"signup" => "http://www.flickr.com/services/"
		),
		"indeed" => array(
			"name" => "Indeed",			
			"categories" => array("content","regional"), 
			"icon" => "", 
			"signup" => "http://www.indeed.com/jsp/apiinfo.jsp"
		),
		"itunes" => array(
			"name" => "iTunes",			
			"limits" => array("request" => 200, "total" => 200),
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "no"
		),	
		/*"kontentmachine" => array(
			"name" => "Kontent Machine",			
			"limits" => array("request" => 200, "total" => 200),
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "wprobot.net/go/kontentmachine"
		),	*/		
		"linkshare" => array(
			"name" => "Linkshare",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://www.linkshare.com/",			
		),
		"oodle" => array(
			"name" => "Oodle",			
			"unique_direct" => "id",	
			"categories" => array("content","regional"), 
			"icon" => "", 
			"signup" => "http://developer.oodle.com/oodle-api"
		),
		/*"photobucket" => array(
			"name" => "Photobucket",			
			"limits" => array("request" => 50, "total" => 100000),				
			"categories" => array("media"), 
			"icon" => "", 
			"signup" => "http://photobucket.com/developer"
		),	*/
		"pixabay" => array(
			"name" => "Pixabay",			
			"limits" => array("request" => 100, "total" => 1000),			
			"categories" => array("media"), 
			"icon" => "", 
			"signup" => "no"
		),	
		/*"prnewswire" => array(
			"name" => "PR Newswire",	
			"limits" => array("request" => 100, "total" => 500),
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "http://api.prnewswire.com/user/jsp/register.jsp"
		),*/			
		"prosperent" => array(
			"name" => "Prosperent",	
			"limits" => array("request" => 100, "total" => 500),
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://prosperent.com/"
		),			
		/*"recipepuppy" => array(
			"name" => "Recipe Buddy",			
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "no"
		),*/	
		"rottentomatoes" => array(
			"name" => "Rotten Tomatoes",			
			"limits" => array("request" => 30, "total" => 100),	
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "http://developer.rottentomatoes.com/"
		),			
		"rss" => array(
			"name" => "RSS Feeds",			
			"categories" => array("content"), 
			"icon" => "", 
			"signup" => "no"
		),			
		"spinchimp" => array(
			"name" => "Spinchimp",			
			"categories" => array("rewriting"), 
			"icon" => "", 
			"signup" => "http://542c4iv7ejm1cq6-snxenhk6kz.hop.clickbank.net/",
			"paid" => 1
		),	
		"spinnerchief" => array(
			"name" => "Spinnerchief",			
			"categories" => array("rewriting"), 
			"icon" => "", 
			"signup" => "http://www.spinnerchief.com/",
			"paid" => 1
		),
		"spinrewriter" => array(
			"name" => "Spinrewriter",			
			"categories" => array("rewriting"), 
			"icon" => "", 
			"signup" => "http://www.spinrewriter.com/?ref=6967",
			"paid" => 1
		),			
		"shopzilla" => array(	
			"name" => "Shopzilla",			
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://publisher.shopzilla.com/reg_page1.xhtml"
		),
		"skimlinks" => array(
			"name" => "Skimlinks",			
			"categories" => array("shopping"), 
			"limits" => array("request" => 300, "total" => 1000),	
			"icon" => "", 
			"signup" => "http://api-products.skimlinks.com"
		),		
		"thebestspinner" => array(
			"name" => "TheBestSpinner",			
			"categories" => array("rewriting"), 
			"icon" => "", 
			"signup" => "http://paydotcom.net/r/96144/thoefter/26522731/",
			"paid" => 1
		),	
		"tradedoubler" => array(
			"name" => "Tradedoubler",			
			"limits" => array("request" => 10000, "total" => 100000),	
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://tradedoubler.com"
		),				
		"vimeo" => array(
			"name" => "Vimeo",			
			"limits" => array("request" => 50, "total" => 100000),	
			"categories" => array("media"), 
			"icon" => "", 
			"signup" => "http://vimeo.com/api"
		),	
		"walmart" => array(
			"name" => "Walmart",			
			"limits" => array("request" => 50, "total" => 100000),	
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "https://developer.walmartlabs.com"
		),			
		"wordai" => array(
			"name" => "WordAI",			
			"categories" => array("rewriting"), 
			"icon" => "", 
			"signup" => "http://wprobot.net/go/wordai",
			"paid" => 1
		),			
		"yelp" => array(
			"name" => "Yelp",			
			"categories" => array("content","regional"), 
			"icon" => "", 
			"signup" => "http://www.yelp.com/developers/documentation/v2/overview"
		),		
		"youtube" => array(
			"name" => "Youtube",			
			"categories" => array("media","comments"), 
			"icon" => "", 
			"signup" => "https://console.developers.google.com/apis/credentials"
		),	
		"zanox" => array(
			"name" => "Zanox",			
			"limits" => array("request" => 50, "total" => 100000),	
			"categories" => array("shopping"), 
			"icon" => "", 
			"signup" => "http://zanox.com"
		),		
	),		
);

/*** OPTION EXPLANATIONS ***/
$optionsexpl = array(
	"general" => array(
		"options" => array(
		),		
	),
	"youtube" => array(
		"options" => array(
			"width" => array("explanation" => "The video width in pixels.", "link" => ""),	
			"height" => array("explanation" => "The video height in pixels.", "link" => ""),	
			"channel" => array("explanation" => "Enter a Youtube channel name to restrict searches to videos by that channel.", "link" => ""),	
		),		
	),		
	"vimeo" => array(
		"options" => array(
			"width" => array("explanation" => "The video width in pixels.", "link" => ""),	
			"height" => array("explanation" => "The video height in pixels.", "link" => ""),	
			"user_id" => array("explanation" => "Enter a Vimeo username to restrict searches to videos uploaded by the user.", "link" => ""),	
		),		
	),	
	"zanox" => array(
		"options" => array(
			"minprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),	
			"maxprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),	
		),		
	),		
	"etsy" => array(
		"options" => array(
			"minprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),	
			"maxprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),	
		),		
	),	
	"tradedoubler" => array(
		"options" => array(
			"minprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),	
			"maxprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),	
			"language" => array("explanation" => "Restrict searches to a specific language by entering its code, e.g. 'de' for German or 'es' for SPanish.", "link" => ""),	
		),		
	),	
	"amazon" => array(
		"options" => array(
			"searchindex" => array("explanation" => "Limits the search to a specific product category. Please note for 'All' Amazon only returns the first 50 products, so select a specific one if possible.", "link" => ""),			
			"minprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),	
			"maxprice" => array("explanation" => __("Maximum price to search for in USD, e.g. '100' without quotes.", "wprobot"), "link" => ""),	
			"minoff" => array("explanation" => __("Use to search for deals and special offers, e.g. enter '30' to only return items with 30% off or more.", "wprobot"), "link" => ""),	
		),		
	),	
	"prosperent" => array(
		"options" => array(
			"merchant" => array("explanation" => "Name of the merchant you want to limit the search to, e.g. 'Zappos' without quotes.", "link" => ""),			
			"minprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),	
			"maxprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),	
		),		
	),
	"indeed" => array(
		"options" => array(					
			"location" => array("explanation" => 'Use a postal code or a "city, state/province/region" combination.', "link" => ""),
			"radius" => array("explanation" => "Maximum distance from search location in km, e.g. '30'.", "link" => ""),	
		),		
	),	
	"linkshare" => array(
		"options" => array(
			"merchant" => array("explanation" => "Optionally specify a merchant (MID) to limit the search to that merchant’s products.", "link" => ""),
		),			
	),	
	"avantlink" => array(
		"options" => array(
			"websiteid" => array("explanation" => "", "link" => "The website identifier from your Avantlink account."),		
			"lowprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),
			"highprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),
			"advertisers" => array("explanation" => 'A pipe-delimited list of AvantLink assigned merchant identifiers, e.g. "123|456"', "link" => ""),		
		),		
	),
	"shopzilla" => array(
		"options" => array(	
			"lowprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),
			"highprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),
			"offers" => array("explanation" => "Number of offers to return per product.", "link" => ""),		
		)
	),		
	"commissionjunction" => array(
		"options" => array(
			"websiteid" => array("explanation" => "The website identifier from your CJ account.", "link" => ""),			
			"lowprice" => array("explanation" => "Minimum price to search for in USD, e.g. '50' without quotes.", "link" => ""),
			"highprice" => array("explanation" => "Maximum price to search for in USD, e.g. '100' without quotes.", "link" => ""),
			"advertisers" => array("explanation" => "Enter 'joined' to search all advertisers you have joined in CJ or a list of one or more advertiser CIDs, separated by comma.", "link" => ""),		
		),
	),
	"eventful" => array(
		"options" => array(
			"cat" => array("explanation" => "", "link" => ""),			
			"location" => array("explanation" => 'Locations in the form "San Diego", "San Diego, TX", "London, United Kingdom", and "Calgary, Alberta, Canada" are accepted, as are postal codes ("92122") and full addresses ("1 Infinite Loop, Cupertino, CA").', "link" => ""),	
		),		
	),
	"oodle" => array(
		"options" => array(
			"cat" => array("explanation" => "Follow this link to see all available categories you can enter.", "link" => "http://developer.oodle.com/categories-list"),			
			"location" => array("explanation" => "You can enter a ZIP code, city, state, location and other values. Click for more details.", "link" => "http://developer.oodle.com/listings#location"),	
			"radius" => array("explanation" => "Maximum distance from search location in km, e.g. '30'.", "link" => ""),	
		),	
	),	
	"yelp" => array(
		"options" => array(
			"location" => array("explanation" => 'Required! Specifies the combination of "address, neighborhood, city, state or zip, optional country" to be used when searching for businesses.', "link" => ""),		
			"radius" => array("explanation" => "Maximum distance from search location in km, e.g. '30'.", "link" => ""),	
		),	
	),
	"datafeed" => array(
		"display" => "no",
		"name" => "CSV Datafeeds",
		"options" => array(
			"delimiter" => array("explanation" => "How fields are separated in your CSV file, commonly by a semicolon, comma or pipe character.", "link" => ""),	
			"enclosure" => array("explanation" => "How fields are enclosed in your CSV file, commonly by a single quote.", "link" => ""),				
			"title" => array("explanation" => "Which number in your CSV file from the left is the title? E.g. if the 1st enter '1'.", "link" => ""),
			"url" => array("explanation" => "Which number in your CSV file from the left is the product URL? E.g. if the 1st enter '1'.", "link" => ""),
			"price" => array("explanation" => "Which number in your CSV file from the left is the price? E.g. if the 1st enter '1'.", "link" => ""),
			"thumbnail" => array("explanation" => "Which number in your CSV file from the left is the thumbnail URL? E.g. if the 1st enter '1'.", "link" => ""),
			"description" => array("explanation" => "Which number in your CSV file from the left is the product description? E.g. if the 1st enter '1'.", "link" => ""),
			"unique" => array("explanation" => "Which number in your CSV file from the left is the unique identifier? E.g. if the 1st enter '1'.", "link" => "")	
		),	
	),		
);

?>