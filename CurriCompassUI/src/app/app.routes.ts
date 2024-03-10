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

export const routes: Routes = [
    { path: 'login', component: LoginUiComponent },
    { path: '', component: BaselayoutComponent, children: [

        { path : 'profile', component: ProfilePageComponent },

        {path: 'users', children: [
          { path : '', component: UsersComponent },
          { path : 'add-user', component: UserFormComponent },
          { path : 'edit-user/:id', component: EditUserFormComponent },
        ]},

        {
          path: 'programs', children: [
            { path : '', component: ProgramListComponent },
            { path : 'add-program', component: ProgramsFormComponent },
          ]
        },

        { path: 'courses', children: [
          { path: '', component: CoursesListComponent },
          { path : 'add-course', component: CourseFormComponent },
        ]},

        {path: 'curricula', children: [
          { path : '', component: CurriculaListComponent },
          { path : 'add-curriculum', component: AddCurriculumComponent },
        ]},

        { path : 'students', children: [
          {path : '', component: StudentsListingComponent },
          { path : 'add-student', component: StudentFormComponent },
          { path : ':id', component: StudentRecordManagementComponent },
        ]},
      ]
    },


];
