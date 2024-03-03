import { Routes } from '@angular/router';
import { StudentFormComponent } from './student-form/student-form.component';
import { LoginUiComponent } from './login-ui/login-ui.component';
import { BaselayoutComponent } from './components/baselayout/baselayout.component';
import { UserFormComponent } from './user-form/user-form.component';
import { ProgramsFormComponent } from './programs-form/programs-form.component';
import { CourseFormComponent } from './course-form/course-form.component';
import { EditUserFormComponent } from './edit-user-form/edit-user-form.component';
import { AddCurriculumComponent } from './add-curriculum/add-curriculum.component';

export const routes: Routes = [
    { path: 'login', component: LoginUiComponent },
    { path: '', component: BaselayoutComponent, children: [
        { path : 'add-student', component: StudentFormComponent },
        { path : 'add-user', component: UserFormComponent },
        { path : 'add-program', component: ProgramsFormComponent },
        { path : 'add-course', component: CourseFormComponent },
        { path : 'add-curriculum', component: AddCurriculumComponent },
        { path : 'edit-user', component: EditUserFormComponent },
      ]
    },


];
