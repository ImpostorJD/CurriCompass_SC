import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { FormArrayControlUtilsService } from '../../services/form-array-control-utils.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-user-form',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink
  ],
  templateUrl: './user-form.component.html',
  styleUrl: './user-form.component.css'
})
export class UserFormComponent {
  constructor(
    private fb : FormBuilder,
    private fac: FormArrayControlUtilsService,
    private router: Router,
    ){}
    private req : HttpReqHandlerService = inject(HttpReqHandlerService);
    private auth: AuthService = inject(AuthService);

    roles: Array<any> = null!;
    selectedRoles: Array<any> = [];

    userField =  this.fb.group({
      "userfname" : new FormControl('', [Validators.required]),
      "userlname" : new FormControl('', [Validators.required]),
      "usermiddle" : new FormControl('', [Validators.required]),
      "contactno" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
      "email" : new FormControl('', [Validators.required, Validators.email]),
      "password" : new FormControl('', [Validators.required]),
      "roles" : this.fb.array([]),
    })

    get rolesFormArray() {
      return this.userField.get('roles') as FormArray;
    }

    addRolesArray() {
        this.selectedRoles.push(null);
        const role: any = this.fb.group({
          roleid: new FormControl(null, [Validators.required])
        });
        this.fac.addControl(this.rolesFormArray, role);
    }

    getRoleControl(index: number): FormControl{
      return this.fac.getFormControl(index, this.rolesFormArray, 'roleid');
    }

    popRolesArray(index : number){
      this.selectedRoles.splice(index, 1);
      this.fac.popControl(this.rolesFormArray, index);
    }

    roleSelected(index: number, event: any) {
      const roleId = event.target.value;
      this.selectedRoles[index] = parseInt(roleId);
    }

    isRoleSelected(roleId: number): boolean {
      return this.selectedRoles.includes(roleId);
    }

    handleSubmit(){
      if(this.userField.status == "INVALID"){
        markFormGroupAsDirtyAndInvalid(this.userField);
        return;
      }

      if(this.rolesFormArray.length <= 0) {
        return;
      }

       this.req.postResource('users/register', this.userField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: () => {

          this.router.navigateByUrl('/users')
        },
        error: err => {
          if(err.status == 409) {
            console.log(err.status);
            this.userField.get('email')?.setErrors({duplicate: true});
          }
        }
       })

    }

    removeError(control: any, error: any): void {
      control.setErrors(error);
    }

    ngOnInit(){
      this.req.getResource('roles', httpOptions(this.auth.getCookie('user')))
        .subscribe({
          next: (res:any) => {
            this.roles = res[1].filter((r:any) => r.rolename !== "Student");
          },
          error: err => console.log(err)
        });

    }
}
