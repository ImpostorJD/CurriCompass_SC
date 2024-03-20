import { Routes } from '@angular/router';
import { StudentFormComponent } from './pages/student-form/student-form.component';
import { LoginUiComponent } from './pages/login-ui/login-ui.component';
import { BaselayoutComponent } from './components/baselayout/baselayout.component';
import { UserFormComponent } from './pages/user-form/user-form.component';
import { ProgramsFormComponent } from './pages/programs-form/programs-form.component';
import { CourseFormComponent } from './pages/course-form/course-form.component';
import { EditUserFormComponent } from './pages/edit-user-form/edit-user-form.component';
import { AddCurriculumComponent } from './pages/add-curriculum/add-curriculum.component';
import { StudentRecordManagementComponent } from './pages/student-record-management/student-record-management.component';
import { StudentsListingComponent } from './pages/students-listing/students-listing.component';
import { ProgramListComponent } from './pages/program-list/program-list.component';
import { UsersComponent } from './pages/users/users.component';
import { ProfilePageComponent } from './pages/profile-page/profile-page.component';
import { CurriculaListComponent } from './pages/curricula-list/curricula-list.component';
import { CoursesListComponent } from './pages/courses-list/courses-list.component';
import { EditCourseComponent } from './pages/edit-course/edit-course.component';
import { EditProgramsComponent } from './pages/edit-programs/edit-programs.component';
import { EditCurriculumComponent } from './pages/edit-curriculum/edit-curriculum.component';
import { SchoolYearPageComponent } from './pages/school-year-page/school-year-page.component';
import { EditSchoolYearComponent } from './pages/edit-school-year/edit-school-year.component';
import { AddSchoolYearComponent } from './pages/add-school-year/add-school-year.component';
import { AnonGuard } from './services/interceptors/anon-access.guard';

export const routes: Routes = [
    {
      path: 'login',
      component: LoginUiComponent,
      canActivate: [AnonGuard]
    },

    { path: '', component: BaselayoutComponent, children: [

        { path : 'profile', component: ProfilePageComponent },

        { path: 'users', children: [
            { path : '', component: UsersComponent },
            { path : 'add-user', component: UserFormComponent },
            { path : ':id', component: EditUserFormComponent },
          ]
        },

        {
          path: 'programs', children: [
            { path : '', component: ProgramListComponent },
            { path : 'add-program', component: ProgramsFormComponent },
            { path : ':id', component: EditProgramsComponent },
          ]
        },

        { path: 'courses', children: [
            { path: '', component: CoursesListComponent },
            { path : 'add-course', component: CourseFormComponent },
            { path : ':id', component: EditCourseComponent },
          ]
        },

        { path: 'curricula', children: [
            { path : '', component: CurriculaListComponent },
            { path : 'add-curriculum', component: AddCurriculumComponent },
            { path : ':id', component: EditCurriculumComponent },
          ]
        },

        { path : 'students', children: [
            { path : '', component: StudentsListingComponent },
            { path : 'add-student', component: StudentFormComponent },
            { path : ':id', component: StudentRecordManagementComponent },
          ]
        },
        { path: 'school-calendar', children: [
            { path: '', component: SchoolYearPageComponent },
            { path: 'add-school-year', component: AddSchoolYearComponent },
            { path: ':id', component: EditSchoolYearComponent },
          ]
        },
      ]
    },


];
