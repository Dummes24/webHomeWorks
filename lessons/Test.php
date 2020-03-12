<?php

namespace lessons;

include "Person.php";
include "Teacher.php";
include "Student.php";
include "Lesson.php";

$teachers = [];
$students = [];
$lessons = [];

#Create Teachers
$teachers[] = new Teacher("Tom", "Ucitel");
$teachers[] = new Teacher("Joe", "Vyucujici");

#Create Students
$students[] = new Student("Pepa", "Student");
$students[] = new Student("Jan", "Zak");
$students[] = new Student("Fíla", "Učeň");

#Create Lessons with created Teachers and Studnets
$lesson1 = new Lesson($teachers[0], [$students[0], $students[1]], "SB101", "Th 12:00");
$lesson2 = new Lesson($teachers[1], [$students[1], $students[2]], "SB101", "Th 14:30");

$lessons[] = $lesson1;
$lessons[] = $lesson2;

echo "<pre>";
var_dump($lessons);
echo "</pre>";