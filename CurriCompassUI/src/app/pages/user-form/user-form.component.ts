import { Component } from '@angular/core';
import { AbstractControl, FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';

//TODO: Add role based access and render

@Component({
  selector: 'app-user-form',
  standalone: true,
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    CommonModule,
    RouterLink
  ],
  providers: [HttpReqHandlerService],
  templateUrl: './user-form.component.html',
  styleUrl: './user-form.component.css'
})
export class UserFormComponent {
  constructor(
    private fb : FormBuilder,
    private req : HttpReqHandlerService,
    private router: Router
    ){}

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
       this.req.postResource('users/register', this.userField.value, httpOptions).subscribe({
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

    }
}
