<?php
/**
 * Class Accredible_Learndash_Learndash_Utils_Test
 *
 * @package Accredible_Learndash_Add_On
 */

defined( 'ABSPATH' ) || die;

require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/learndash/class-accredible-learndash-learndash-utils.php';
require_once ACCREDILBE_LEARNDASH_PLUGIN_PATH . '/includes/learndash/class-accredible-learndash-learndash-functions.php';

/**
 * Unit tests for Accredible_Learndash_Learndash_Utils
 */
class Accredible_Learndash_Learndash_Utils_Test extends WP_UnitTestCase {
	/**
	 * Test if it returns a list of lessons that belong to a course.
	 */
	public function test_get_lesson_options() {
		$data1 = array(
			'post_title' => 'Test Course Title 1',
			'post_type'  => 'sfwd-lessons',
		);
		$data2 = array(
			'post_title' => 'Test Course Title 2',
			'post_type'  => 'sfwd-lessons',
		);
		$id1   = self::factory()->post->create( $data1 );
		$id2   = self::factory()->post->create( $data2 );

		$mockbuiltin = $this->getMockBuilder( 'Accredible_Learndash_Learndash_Functions' )
			->setMethods( array( 'learndash_get_course_lessons_list' ) )
			->getMock();
		$mockres     = array(
			array(
				'sno'                => 1,
				'id'                 => $id1,
				'post'               => get_post( $id1 ),
				'permalink'          => 'http://127.0.0.1:8000/?p=56',
				'class'              => '',
				'status'             => 'notcompleted',
				'sample'             => 'is_not_sample',
				'sub_title'          => '',
				'lesson_access_from' => null,
			),
			array(
				'sno'                => 2,
				'id'                 => $id2,
				'post'               => get_post( $id2 ),
				'permalink'          => 'http://127.0.0.1:8000/?p=11',
				'class'              => '',
				'status'             => 'completed',
				'sample'             => 'is_not_sample',
				'sub_title'          => '',
				'lesson_access_from' => '',
			),
		);
		$mockbuiltin->method( 'learndash_get_course_lessons_list' )
			->with( $this->equalTo( 1 ) )
			->willReturn( $mockres );

		$expected = array(
			$id1 => $data1['post_title'],
			$id2 => $data2['post_title'],
		);

		$utils  = new Accredible_Learndash_Learndash_Utils( $mockbuiltin );
		$result = $utils->get_lesson_options( 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test if it returns a parent course.
	 */
	public function test_get_lesson_options_when_not_found() {
		$mockbuiltin = $this->getMockBuilder( 'Accredible_Learndash_Learndash_Functions' )
			->setMethods( array( 'learndash_get_course_lessons_list' ) )
			->getMock();
		$mockbuiltin->method( 'learndash_get_course_lessons_list' )
			->with( $this->equalTo( 1 ) )
			->willReturn( array() );

		$utils  = new Accredible_Learndash_Learndash_Utils( $mockbuiltin );
		$result = $utils->get_lesson_options( 1 );
		$this->assertEquals( array(), $result );
	}

	/**
	 * Test if it return empty array when no lesson is found.
	 */
	public function test_get_parent_course() {
		$course_data = array(
			'post_title' => 'Test Course Title 1',
			'post_type'  => 'sfwd-courses',
		);
		$course_id   = self::factory()->post->create( $course_data );

		$mockbuiltin = $this->getMockBuilder( 'Accredible_Learndash_Learndash_Functions' )
			->setMethods( array( 'learndash_get_course_id' ) )
			->getMock();
		$mockbuiltin->method( 'learndash_get_course_id' )
			->with( $this->equalTo( 1 ) )
			->willReturn( $course_id );

		$expected = get_post( $course_id );
		$utils    = new Accredible_Learndash_Learndash_Utils( $mockbuiltin );
		$result   = $utils->get_parent_course( 1 );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test if it return null when no post is found.
	 */
	public function test_get_parent_course_when_not_found() {
		$course_data = array(
			'post_title' => 'Test Course Title 1',
			'post_type'  => 'sfwd-courses',
		);
		$course_id   = self::factory()->post->create( $course_data );

		$mockbuiltin = $this->getMockBuilder( 'Accredible_Learndash_Learndash_Functions' )
			->setMethods( array( 'learndash_get_course_lessons_list' ) )
			->getMock();
		$mockbuiltin->method( 'learndash_get_course_lessons_list' )
			->with( $this->equalTo( 1 ) )
			->willReturn( 0 );

		$utils  = new Accredible_Learndash_Learndash_Utils( $mockbuiltin );
		$result = $utils->get_parent_course( 1 );
		$this->assertEquals( null, $result );
	}

	/**
	 * Test if it return null when the found post is not a course.
	 */
	public function test_get_parent_course_when_found_post_is_not_course() {
		$data = array(
			'post_title' => 'Test Course Title 1',
			'post_type'  => 'sfwd-lessons',
		);
		$id   = self::factory()->post->create( $data );

		$mockbuiltin = $this->getMockBuilder( 'Accredible_Learndash_Learndash_Functions' )
			->setMethods( array( 'learndash_get_course_lessons_list' ) )
			->getMock();
		$mockbuiltin->method( 'learndash_get_course_lessons_list' )
			->with( $this->equalTo( 1 ) )
			->willReturn( $id );

		$utils  = new Accredible_Learndash_Learndash_Utils( $mockbuiltin );
		$result = $utils->get_parent_course( 1 );
		$this->assertEquals( null, $result );
	}
}
