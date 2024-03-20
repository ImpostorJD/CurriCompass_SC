import { Component, ViewChild } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { DatePickerComponent } from '../../components/date-picker/date-picker.component';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import moment from 'moment';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-edit-school-year',
  standalone: true,
  imports: [
    DatePickerComponent,
    RouterLink,
    ReactiveFormsModule,
    HttpClientModule
  ],
  providers:[
    HttpReqHandlerService
  ],
  templateUrl: './edit-school-year.component.html',
  styleUrl: './edit-school-year.component.css'
})
export class EditSchoolYearComponent {
  constructor(
    private fb: FormBuilder,
    private req: HttpReqHandlerService,
    private route: Router,
    private activeRouter: ActivatedRoute
  ){}
  @ViewChild(DatePickerComponent, { static: false }) datePickerComponent!: DatePickerComponent;
  routerId:number = null!;
  schoolYear:any = null;

  schoolYearField = this.fb.group({
    sy_start: new FormControl('', [Validators.required]),
    sy_end: new FormControl('', [Validators.required]),
  });

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

    this.req.patchResource('school-year/' + this.routerId, this.schoolYearField.value, httpOptions).subscribe({
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

  ngOnInit() {
    this.activeRouter.params.subscribe(params => {
      this.routerId = parseInt(params['id']);
      this.req.getResource('school-year/' + this.routerId, httpOptions).subscribe({
        next: (res:any) => {
          this.schoolYear = res[1];
          this.schoolYearField.get('sy_start')!.patchValue(moment(res[1].sy_start).format("YYYY/MM/DD"));
          this.schoolYearField.get('sy_end')!.patchValue(moment(res[1].sy_end).format("YYYY/MM/DD"));
        },

        error: error => console.error(error),
      });
    });
  }

}
