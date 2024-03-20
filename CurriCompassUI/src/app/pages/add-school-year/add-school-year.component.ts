import { Component } from '@angular/core';
import { DatePickerComponent } from '../../components/date-picker/date-picker.component';
import { Router, RouterLink } from '@angular/router';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import moment from 'moment';
import { HttpClientModule } from '@angular/common/http';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { AuthService } from '../../services/auth.service';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-add-school-year',
  standalone: true,
  imports: [
    DatePickerComponent,
    RouterLink,
    ReactiveFormsModule,
    HttpClientModule
  ],
  providers:[
    HttpReqHandlerService,
    AuthService,
  ],
  templateUrl: './add-school-year.component.html',
  styleUrl: './add-school-year.component.css'
})
export class AddSchoolYearComponent {

  constructor(
    private fb: FormBuilder,
    private req: HttpReqHandlerService,
    private route: Router,
    private auth: AuthService
  ){}

  schoolYearField = this.fb.group({
    sy_start: new FormControl('', [Validators.required]),
    sy_end: new FormControl('', [Validators.required]),
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
    let date1 = moment(this.schoolYearField.get('sy_start')!.value, "YYYY/MM/DD");
    let date2 = moment(this.schoolYearField.get('sy_end')!.value, "YYYY/MM/DD");
    const datediff = date2.diff(date1, "years", true);

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
        this.route.navigateByUrl('/school-calendar')
      },
      error: err => {
        if (err.status === 409) {
          this.schoolYearField.get('sy_start')!.setErrors({'duplicate': true});
        }
      }
    });
  }

}
