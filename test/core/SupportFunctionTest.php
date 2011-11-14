<?
require_once dirname(__FILE__) . './../test_helper.php';
require_once "SupportFunction.php";
class SupportFunctionTest extends PHPUnit_Framework_TestCase
{
  public function test_is_presence_should_return_true()
  {
    //any have variables
    $fixture = "test";

    $result = is_presence($fixture);
    $this->assertEquals($result, true);

    //when array
    $fixture = array("hoge" => "piyo");

    $result = is_presence($fixture);
    $this->assertEquals($result, true);

  }

  public function test_is_blank_should_return_true() {
    $this->assertEquals(is_blank(null), true) ;//when null
    $this->assertEquals(is_blank(array()), true); //when empty array
    $this->assertEquals(is_blank(""), true); //when blank string("")
  }
}

