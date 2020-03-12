<?php


namespace lessons;
/***
 * Class Lesson - Defines teacher, students, room and time of lesson
 *
 * @package lessons
 *
 * @property Teacher $teacher Instance of class Teacher
 * @property array $students Array of in instances of class Student
 * @property string $room Room name (SB101)
 * @property string $time Day and Time of lesson (Th 12:00)
 */
class Lesson
{
    public $teacher;
    public $students;
    public $room;
    public $time;

    public function __construct($teacher, $students, $room, $time)
    {
        $this->teacher = $teacher;
        $this->students = $students;
        $this->room = $room;
        $this->time = $time;
    }
}