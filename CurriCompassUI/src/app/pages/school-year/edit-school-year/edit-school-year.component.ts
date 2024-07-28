import { Component, inject } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { DatePickerComponent } from '../../../components/date-picker/date-picker.component';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import moment from 'moment';
import { httpOptions } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-edit-school-year',
  standalone: true,
  imports: [
    DatePickerComponent,
    RouterLink,
    ReactiveFormsModule,
    LoadingComponentComponent,
  ],
  templateUrl: './edit-school-year.component.html',
  styleUrl: './edit-school-year.component.css'
})
export class EditSchoolYearComponent {
  constructor(
    private fb: FormBuilder,
    private route: Router,
    private activeRouter: ActivatedRoute,
    public loading: SystemLoadingService
  ){}
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  routerId:number = null!;
  schoolYear:any = null;

  schoolYearField = this.fb.group({
    sy_start: new FormControl('', [Validators.required, Validators.pattern(/^[0-9]{4}$/)]),
    sy_end: new FormControl('', [Validators.required, Validators.pattern(/^[0-9]{4}$/)]),
  });

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

    this.req.patchResource('school-year/' + this.routerId, this.schoolYearField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
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
    this.loading.initLoading();
    this.activeRouter.params.subscribe(params => {
      this.routerId = parseInt(params['id']);
      this.req.getResource('school-year/' + this.routerId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (res:any) => {
          this.schoolYear = res[1];
          this.schoolYearField.get('sy_start')!.patchValue(res[1].sy_start);
          this.schoolYearField.get('sy_end')!.patchValue(res[1].sy_end);
          this.loading.endLoading();
        },

        error: error => console.error(error),
      });
    });
  }

}
