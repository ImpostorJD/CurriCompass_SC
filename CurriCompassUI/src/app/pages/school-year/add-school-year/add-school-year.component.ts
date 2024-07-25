import { Component, inject } from '@angular/core';
import { DatePickerComponent } from '../../../components/date-picker/date-picker.component';
import { Router, RouterLink } from '@angular/router';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import moment from 'moment';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-add-school-year',
  standalone: true,
  imports: [
    DatePickerComponent,
    RouterLink,
    ReactiveFormsModule,
  ],
  templateUrl: './add-school-year.component.html',
  styleUrl: './add-school-year.component.css'
})
export class AddSchoolYearComponent {

  constructor(
    private fb: FormBuilder,
    private route: Router,
    ){}
    private auth: AuthService = inject(AuthService);
    private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  schoolYearField = this.fb.group({
    sy_start: new FormControl('', [Validators.required, Validators.pattern(/^[0-9]{4}$/)]),
    sy_end: new FormControl('', [Validators.required, Validators.pattern(/^[0-9]{4}$/)]),
  });

  setYearStart(value: string) {
    this.schoolYearField.get('sy_start')?.setValue(value);
    this.schoolYearField.get('sy_start')?.setErrors({
      'less_than' : false,
      'equal': false,
      'duplicate': false,
    });
  }

  setYearEnd(value: string) {
    this.schoolYearField.get('sy_end')?.setValue(value);
  }

  handleSubmit(){
    let date1 = parseInt(this.schoolYearField.get('sy_start')!.value!);
    let date2 =  parseInt(this.schoolYearField.get('sy_end')!.value!);

    let datediff = date2 - date1;

    if(datediff < 0){
      this.schoolYearField.get('sy_start')!.setErrors({'less_than': true});
      return;
    }

    if(datediff == 0){
      this.schoolYearField.get('sy_start')!.setErrors({'equal': true});
      return;
    }

    this.req.postResource('school-year', this.schoolYearField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.route.navigateByUrl('/school-year')
      },
      error: err => {
        if (err.status === 409) {
          this.schoolYearField.get('sy_start')!.setErrors({'duplicate': true});
        }
      }
    });
  }

}
