<?php

require_once dirname(__FILE__) . '/../../model/Post.php';

/**
 * Test class for Post.
 * Generated by PHPUnit on 2011-07-01 at 14:40:32.
 */
class PostTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Post
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Post;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * Test pull function
     */
    public function testPull() {
        $this->assertEquals(1, $this->object->set_id(1)->size());
        $this->assertNull($this->object->author);
        $this->assertNull($this->object->reply);
        $this->assertTrue($this->object->pull());
        $this->assertEquals('Hello world', $this->object->content);
        $this->assertTrue($this->object->author->pull());
        $this->assertEquals('hovey', $this->object->author->name);
        $this->assertNull($this->object->reply);
    }

    /**
     * Test pull function
     */
    public function testPull2() {
        $this->assertEquals(1, $this->object->set_id(2)->size());
        $this->assertNull($this->object->author);
        $this->assertNull($this->object->reply);
        $this->assertTrue($this->object->pull());
        $this->assertEquals('Hello world', $this->object->content);
        $this->assertTrue($this->object->author->pull());
        $this->assertEquals('hovey', $this->object->author->name);
        $this->assertNotNull($this->object->reply);
        $this->assertTrue($this->object->reply->pull());
        $this->assertNotNull($this->object->reply->author);
        $this->assertTrue($this->object->reply->author->pull());
        $this->assertEquals('hovey', $this->object->reply->author->name);
        $this->assertEquals('Hello world', $this->object->reply->content);
    }

    public function testInsert() {
        $newobj = new Post;
        $newobj->author = '=1101';
        $newobj->set_constraints('author');
        $this->assertGreaterThan(0, $org_size = $newobj->size());
        $this->object->content="Unit Test' AND TRUE";
        $this->object->author=1101;
        $this->object->reply = 1;
        $this->assertTrue($this->object->push());
        $this->assertTrue($this->object->get_id() > $org_size);
    }
    
    public function testLimit() {
        $newobj = new Post;
        $newobj->author = '= 1101';
        $newobj->set_constraints('author');
        $this->assertGreaterThan(0, $org_size = $newobj->size());
        
        $newobj2 = new Post;
        $newobj2->author = '= 1101';
        $newobj2->set_constraints('author');
        $newobj2->set_limit($org_size-1);
        $counting = 0;
        while ($newobj2->pull()) {
            ++$counting;
        }
        $this->assertTrue($counting + 1 == $org_size);
    }
    
    public function testDelete() {
        $newobj = new Post;
        $newobj->author = '= 1101';
        $newobj->set_constraints('author');
        $newobj->orderby('id', 'desc');
        $this->assertGreaterThan(0, $org_size = $newobj->size());
        $this->assertTrue($newobj->pull());
        $this->object->set_id($newobj->get_id());
        $this->assertTrue($this->object->pull());
        $this->assertTrue($this->object->author->pull());
        $this->assertEquals('hovey', $this->object->author->name);
        $this->assertTrue($this->object->remove());
        
        $newobj2 = new Post;
        $newobj2->author = '= 1101';
        $newobj2->set_constraints('author');
        
        $this->assertGreaterThan(0, $now_size = $newobj2->reset()->size());
        $this->assertTrue($now_size == $org_size - 1);        
    }

    /**
     * Generated from @assert () == 'posts'.
     */
    public function testGet_tablename() {
        $this->assertEquals(
                'posts', $this->object->get_tablename()
        );
    }

    /**
     * @todo Implement testGet_prikey().
     */
    public function testGet_prikey() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>
