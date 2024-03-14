import { Component } from '@angular/core';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';

@Component({
  selector: 'app-edit-user-form',
  standalone: true,
  imports: [
    CommonModule,
    HttpClientModule,
    RouterLink,
    ReactiveFormsModule
  ],
  providers:[HttpReqHandlerService],
  templateUrl: './edit-user-form.component.html',
  styleUrl: './edit-user-form.component.css'
})

export class EditUserFormComponent {
  constructor(
    private fb : FormBuilder,
    private req : HttpReqHandlerService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    ){}

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
        this.rolesFormArray.push(role);
    }

    getRoleControl(index: number): FormControl{
      return (this.rolesFormArray.at(index) as FormGroup).get('roleid')! as FormControl;
    }

    popRolesArray(index : number){
      this.selectedRoles.splice(index, 1);
        this.rolesFormArray.removeAt(index);
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

       console.log(this.userField.value);

       let response = null;
       this.req.patchResource('users/' + this.routeId, this.userField.value, httpOptions).subscribe({
        next: data => {
          response = data;
          console.log(response);
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
      this.req.getResource('roles', httpOptions)
        .subscribe({
          next: (res:any) => {
            this.roles = res[1];
          },
          error: err => console.log(err)
        });

        this.activatedRoute.params.subscribe(params => {
          this.routeId = parseInt(params['id']);

          this.req.getResource('users/' + params['id'], httpOptions).subscribe({
            next: (res: any) => {
              const selectedUser = res[1];
              this.userField.patchValue(selectedUser);
              selectedUser.user_roles.forEach((role:any, index:number) => {
                const roleField: any = this.fb.group({
                  roleid: new FormControl(role.roleid, [Validators.required])
                });
                this.selectedRoles[index] = parseInt(role.roleid);
                this.rolesFormArray.push(roleField);
              });

            },
            error: err => console.log(err),
          });

       });


    }


}
