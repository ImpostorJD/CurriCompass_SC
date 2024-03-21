import { Component, inject } from '@angular/core';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { FormArray, FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../services/form-array-control-utils.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-edit-user-form',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    ReactiveFormsModule
  ],
  templateUrl: './edit-user-form.component.html',
  styleUrl: './edit-user-form.component.css'
})

export class EditUserFormComponent {
  constructor(
    private fb : FormBuilder,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private fac: FormArrayControlUtilsService,
  ){}
  private req : HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

    roles: Array<any> = null!;
    selectedRoles: Array<any> = [];
    routeId: number = null!;

    userField =  this.fb.group({
      "userfname" : new FormControl('', [Validators.required]),
      "userlname" : new FormControl('', [Validators.required]),
      "usermiddle" : new FormControl('', [Validators.required]),
      "contact_no" : new FormControl('', [Validators.required, Validators.pattern(/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/)]),
      "email" : new FormControl('', [Validators.required, Validators.email]),
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
      if(this.rolesFormArray.length <= 0) {
        return;
      }

      if(this.userField.status == "INVALID"){
        markFormGroupAsDirtyAndInvalid(this.userField);
        return;
      }

       this.req.patchResource('users/' + this.routeId, this.userField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
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

    ngOnInit(){
      this.req.getResource('roles', httpOptions(this.auth.getCookie('user')))
        .subscribe({
          next: (res:any) => {
            this.roles = res[1].filter((r:any) => r.rolename !== "Student");
          },
          error: err => console.log(err)
        });

        this.activatedRoute.params.subscribe(params => {
          this.routeId = parseInt(params['id']);

          this.req.getResource('users/' + params['id'], httpOptions(this.auth.getCookie('user'))).subscribe({
            next: (res: any) => {
              const selectedUser = res[1];
              this.userField.patchValue(selectedUser);
              selectedUser.user_roles.forEach((role:any, index:number) => {
                const roleField: any = this.fb.group({
                  roleid: new FormControl(role.roleid, [Validators.required])
                });
                this.selectedRoles[index] = parseInt(role.roleid);
                this.fac.addControl(this.rolesFormArray, roleField);
              });

            },
            error: err => console.log(err),
          });

       });


    }


}
