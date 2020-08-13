<?php
require 'autoload.php';
use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
if ( !class_exists( 'AmazonProductRequestAC5' ) ) {
class AmazonProductRequestAC5
{
    /* Local path of CA Root Certificates file needed for using the
     * SSL option. Available at http://curl.haxx.se/ca/cacert.pem
     * @type string
     * @access private
     */
    private $CERTPATH = 'cacert.pem';

    /*Constants for data validation. */

    /* List of valid locations.
     * @type array
     * @access private
     */
    private $LOCATIONS = array ('ca','com','cn', 'co.jp', 'co.uk','fr',
                                'it','es', 'de', 'in', 'com.br');

    /* List of locations.
     * @type array
     * @access private
     */
    private $RESPONSEFORMATS = array ('string','simplexml', 'array');

    /* Configuration variables used for the request.
     * @type array
     * @access private
     */
    private $config = array();

    /* Amazon query parameters.
     * array keys in $params are used when sending queries to Amazon, and
     * therefore must be in PascalCase.
     * @type array
     * @access private
     */
    private $params = array();

    /* Expressions used to construct the power search parameter that can be used
     * for the itemSearch operation. These are only useful when SearchIndex is
     * set to 'Books'.
     * @type array
     * @access private
     */
    private $powerStrings = array();

    /* Message that will be populated and returned as an exception should the
     * execution of a request fail.
     * @type string
     * @access private
     */
    private $errorMsg;

    private $accessKey;
    private $secretKey;
    private $tag;
    private $location;

    /* Constructor
     * @param string $keyId
     * @param string $tag
     * @param string $secretKey
     * @param string $version
     * @param string $location optional
     * @access public
     */
    public function __construct($keyId, $tag, $secretKey,
                                $location = 'com')
    {
        try
        {
            $this->accessKey = $keyId;
            $this->secretKey = $secretKey;
            $this->tag = $tag;
            $this->location = $location;
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    public function searchItems( $keyword, $category = 'All', $page = 0 ) {
        // Set Operation
        $operation = new SearchItems();
        $operation->setPartnerTag( $this->tag )->setKeywords( $keyword )->setItemPage( $page )->
        setResources([
            'Images.Primary.Large',
            'Images.Primary.Medium',
            'ItemInfo.Title',
            'ItemInfo.Features',
            'ItemInfo.ProductInfo',
            // 'ItemInfo.ContentInfo',
            // 'ItemInfo.ByLineInfo',
            // 'ItemInfo.Classifications',
            // 'ItemInfo.ManufactureInfo',
            'Offers.Listings.Price'
        ]);

        if( $category != 'All' ) {
            $operation->setSearchIndex( $category );
        }

        // Prapere Request
        $request = new Request( $this->accessKey, $this->secretKey );
        $request->setRegion( $this->location )->setPayload($operation);

        // Send Request & Get Response : JSON
        $response = new Response($request);
        // var_dump($response->body);
        $res = json_decode( $response->body, true );
        // var_dump($res);
        if( isset( $res['SearchResult']['Items'] ) )
            return $res['SearchResult']['Items'];
        return false;
        // var_dump($response->body);
        // return json_decode( $response->body );
    }
}
}