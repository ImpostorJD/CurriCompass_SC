import { Routes } from '@angular/router';
import { UserFormComponent } from './user-form/user-form.component';
import { EditUserFormComponent } from './edit-user-form/edit-user-form.component';
import { UsersComponent } from './users-listing/users.component';
import { ChangePasswordComponent } from './change-password/change-password.component';
import { AuthGuard } from '../../services/auth/auth-access.guard';

export const usersRoutes: Routes = [
  { path : '', component: UsersComponent, canActivate: [AuthGuard(['Admin'])],},
  { path : 'add-user', component: UserFormComponent, canActivate: [AuthGuard(['Admin'])], },
  { path : 'change-password', component: ChangePasswordComponent, canActivate: [AuthGuard(['Admin', 'Staff', 'Student'])], },
  { path : ':id', component: EditUserFormComponent, canActivate: [AuthGuard(['Admin'])], },
];
