import { Routes } from '@angular/router';
import { UserFormComponent } from './user-form/user-form.component';
import { EditUserFormComponent } from './edit-user-form/edit-user-form.component';
import { UsersComponent } from './users-listing/users.component';
import { ChangePasswordComponent } from './change-password/change-password.component';

export const usersRoutes: Routes = [
  { path : '', component: UsersComponent },
  { path : 'add-user', component: UserFormComponent },
  { path : 'change-password', component: ChangePasswordComponent },
  { path : ':id', component: EditUserFormComponent },
];
