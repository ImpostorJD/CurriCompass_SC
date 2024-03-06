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

export const routes: Routes = [
    { path: 'login', component: LoginUiComponent },
    { path: '', component: BaselayoutComponent, children: [
        {path: 'user', children: [
          { path : 'add-user', component: UserFormComponent },
          { path : 'edit-user/:id', component: EditUserFormComponent },
        ]},
        { path : 'add-program', component: ProgramsFormComponent },
        { path : 'program-list', component: ProgramListComponent },
        { path : 'add-course', component: CourseFormComponent },
        { path : 'add-curriculum', component: AddCurriculumComponent },
        {path : 'students-list', component: StudentsListingComponent },
        { path : 'students', children: [
          
          { path : ':id', component: StudentRecordManagementComponent },
          { path : 'add-student', component: StudentFormComponent },
         
          
          
        ]},
      ]
    },


];
