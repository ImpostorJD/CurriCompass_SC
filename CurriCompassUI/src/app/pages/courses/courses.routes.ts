import { Routes } from "@angular/router";
import { CoursesListComponent } from "./courses-list/courses-list.component";
import { CourseFormComponent } from "./course-form/course-form.component";
import { EditCourseComponent } from "./edit-course/edit-course.component";

export const coursesRoutes: Routes = [
  { path: '', component: CoursesListComponent },
  { path : 'add-course', component: CourseFormComponent },
  { path : ':id', component: EditCourseComponent },
];

