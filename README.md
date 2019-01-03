# schedule-ace

Web app for generating the schedule of a faculty

This web application is meant to compute the schedule of a faculty based on a set of given data.
It has a front panel accesible to all faculty teachers and a back panel open only to the app's admin.

In the back panel, the manually entered data (data coming from faculty) consists of students (entered/ordered by groups/subgroups/years), subjects (assigned on students subgroups/teachers), classes, teachers, restrictioned time periods allocated to subgroups (eg. a few student subgroups have a common 2 hours booked on each wednesday over which they have a presentation of some companies students related programs). After all this data is entered and all assignments are made correctly, then on the last module of the back panel a Generate schedule button appears. Through this button, the schedule is generated using a sort of backtracking algorithm.

In the front panel, each teacher can verify its own classes/subjects resulted after the schedule generation from the back panel. In case the teacher is not happy, he can add an exception per day in which he will specify the timestamp he's not available in for faculty. Each teacher has a certain exception level added in the back panel (low/medium/high) over which, after the adding of that exception/s and the following schedule regeneration, the final schedule will be, of course, changed.

As for the used technologies:

a) Front: HTML 5, CSS 3, JavaScript, jQuery 1.11 (along with a few built-on plugins such as nicescroll, timepicker, select2, metisMenu, slimscroll) and Bootstrap 3.

b) Back: PHP vanilla (no framework) and Twig as template engine. In what regards the code, a light attempt of procedural to OOP with MVC as design pattern.

c) Database: relational (MySQL)
