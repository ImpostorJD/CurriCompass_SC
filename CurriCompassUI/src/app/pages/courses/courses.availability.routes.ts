import { Routes } from "@angular/router";
import { CourseAvailabilityComponent } from "./course-availability/course-availability.component";
import { AddCourseAvailabilityComponent } from "./add-course-availability/add-course-availability.component";
import { EditCourseAvailabilityComponent } from "./edit-course-availability/edit-course-availability.component";

export const courseAvailabilityRoutes: Routes = [
  { path: '', component: CourseAvailabilityComponent },
  { path: 'add-course-availability', component: AddCourseAvailabilityComponent },
  { path: ':id', component: EditCourseAvailabilityComponent },
];
