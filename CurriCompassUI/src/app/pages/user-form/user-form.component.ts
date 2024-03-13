import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { httpOptions } from '../../../configs/Constants';
import { HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';

//FIXME: Fix role selection
//TODO: Integrate with API
//TODO: Validate end to end configuration

@Component({
  selector: 'app-user-form',
  standalone: true,
  imports: [HttpClientModule, ReactiveFormsModule, CommonModule],
  providers: [HttpReqHandlerService],
  templateUrl: './user-form.component.html',
  styleUrl: './user-form.component.css'
})
export class UserFormComponent {
  constructor(
    private fb : FormBuilder,
    private http : HttpReqHandlerService,
    ){}

    roles: Array<any> = null!;

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
      const control = new FormControl(null);
      this.rolesFormArray.push(control);
    }

    popRolesArray(index : number){
      this.rolesFormArray.removeAt(index);
    }

    ngOnInit(){
      this.http.getResource('roles', httpOptions)
        .subscribe({
          next: (res:any) => {
            this.roles = res[1];
          },
          error: err => console.log(err)
        });

    }
}
