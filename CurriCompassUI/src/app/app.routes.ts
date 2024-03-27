import { Routes } from '@angular/router';

import { BaselayoutComponent } from './components/baselayout/baselayout.component';

import { LoginUiComponent } from './pages/users/login-ui/login-ui.component';
import { UserFormComponent } from './pages/users/user-form/user-form.component';
import { EditUserFormComponent } from './pages/users/edit-user-form/edit-user-form.component';
import { UsersComponent } from './pages/users/users-listing/users.component';
import { ProfilePageComponent } from './pages/users/profile-page/profile-page.component';

import { StudentFormComponent } from './pages/students/student-form/student-form.component';
import { StudentsListingComponent } from './pages/students/students-listing/students-listing.component';
import { StudentRecordManagementComponent } from './pages/students/student-record-management/student-record-management.component';

import { AddCurriculumComponent } from './pages/curriculum/add-curriculum/add-curriculum.component';
import { CurriculaListComponent } from './pages/curriculum/curricula-list/curricula-list.component';
import { EditCurriculumComponent } from './pages/curriculum/edit-curriculum/edit-curriculum.component';

import { CourseFormComponent } from './pages/courses/course-form/course-form.component';
import { CoursesListComponent } from './pages/courses/courses-list/courses-list.component';
import { EditCourseComponent } from './pages/courses/edit-course/edit-course.component';
import { CourseAvailabilityComponent } from './pages/courses/course-availability/course-availability.component';

import { ProgramsFormComponent } from './pages/program/programs-form/programs-form.component';
import { ProgramListComponent } from './pages/program/program-list/program-list.component';
import { EditProgramsComponent } from './pages/program/edit-programs/edit-programs.component';

import { SchoolYearPageComponent } from './pages/school-year/school-year-page/school-year-page.component';
import { EditSchoolYearComponent } from './pages/school-year/edit-school-year/edit-school-year.component';
import { AddSchoolYearComponent } from './pages/school-year/add-school-year/add-school-year.component';

import { ErrorPageComponent } from './pages/error-page/error-page.component';

import { AnonGuard } from './services/auth/anon-access.guard';
import { AuthGuard } from './services/auth/auth-access.guard';
import { ConsultationPageComponent } from './pages/consultation/consultation-page/consultation-page.component';
import { AddConsultationComponent } from './pages/consultation/add-consultation/add-consultation.component';
import { EditConsultationComponent } from './pages/consultation/edit-consultation/edit-consultation.component';

export const routes: Routes = [

    { path: '',
      component: BaselayoutComponent,
      canActivate:[AuthGuard([])],
      children: [
        {
          path: '', redirectTo: 'consultation', pathMatch:'full'
        },

        {
          path : 'profile',
          component: ProfilePageComponent
        },

        { path: 'users',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path : '', component: UsersComponent },
            { path : 'add-user', component: UserFormComponent },
            { path : ':id', component: EditUserFormComponent },
          ]
        },

        {
          path: 'programs',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path : '', component: ProgramListComponent },
            { path : 'add-program', component: ProgramsFormComponent },
            { path : ':id', component: EditProgramsComponent },
          ]
        },

        { path: 'courses',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
         children: [
            { path: '', component: CoursesListComponent },
            { path : 'add-course', component: CourseFormComponent },
            { path : ':id', component: EditCourseComponent },
          ]
        },

        { path: 'curricula',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path : '', component: CurriculaListComponent },
            { path : 'add-curriculum', component: AddCurriculumComponent },
            { path : ':id', component: EditCurriculumComponent },
          ]
        },

        { path : 'students',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path : '', component: StudentsListingComponent },
            { path : 'add-student', component: StudentFormComponent },
            { path : ':id', component: StudentRecordManagementComponent },
          ]
        },

        { path: 'school-calendar',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path: '', component: SchoolYearPageComponent },
            { path: 'add-school-year', component: AddSchoolYearComponent },
            { path: ':id', component: EditSchoolYearComponent },
          ]
        },

        { path: 'course-availability',
          canActivate: [AuthGuard(['Admin', 'Faculty'])],
          children: [
            { path: '', component: CourseAvailabilityComponent },
          ]
        },

        {
          path: "consultation",
          children: [
            {
              path: '',
              canActivate: [AuthGuard(['Admin', 'Faculty', 'Student'])],
              component: ConsultationPageComponent
            },
            {
              path: 'add-consultation',
              canActivate: [AuthGuard(['Admin', 'Faculty'])],
              component: AddConsultationComponent
            },
            {
              path: ':id',
              canActivate: [AuthGuard(['Admin', 'Faculty'])],
              component: EditConsultationComponent
            },
          ]
        }
      ]
    },

    {
      path: 'login',
      component: LoginUiComponent,
      canActivate: [AnonGuard]
    },

    {
      path: "**", component: ErrorPageComponent
    }

];
