<?
require_once dirname(__FILE__) . './../test_helper.php';
 
class SampleTest extends PHPUnit_Framework_TestCase
{
  public function testNewArrayIsEmpty()
  {
    // 配列を作成します。
    $fixture = array();

    // 配列のサイズは 0 です。
    $this->assertEquals(0, sizeof($fixture));

    $hoge = new Sample();
    $user = $hoge->first_user();
    $this->assertEquals(1, $user["id"]);
  }

  /**
   * @test
   */
  public function arrayContainsAnElementTest()
  {
    // 配列を作成します。
    $fixture = array();
 
    // 配列にひとつの要素を追加します。
    $fixture[] = 'Element';
 
      // 配列のサイズは 1 です。
        $this->assertEquals(1, sizeof($fixture));
  }
}

