import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { AuthService } from '../../../services/auth/auth.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';

@Component({
  selector: 'app-add-semester-availability',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    RouterLink,
  ],
  templateUrl: './add-semester-availability.component.html',
  styleUrl: './add-semester-availability.component.css'
})
export class AddSemesterAvailabilityComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    public dateformat: FormatDateService,
  ){

  }
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  semesters: any = [];
  schoolYears: any = [];

  semsy = this.fb.group({
    sy : new FormControl('', [Validators.required]),
    semid : new FormControl('', [Validators.required]),
  });

  handleSubmit(){
    if(this.semsy.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.semsy);
      return;
    }

    this.req.postResource('semester-management', this.semsy.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/semester-management');
      },
      error: (err:any) => {
        if(err.status == 409){
          this.semsy.get('sy')!.setErrors({'duplicate' :true});
        }
      }
    });

  }

  ngOnInit(){
    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.schoolYears = res[1];
      },
      error: err => console.error(err),
    })

    this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.semesters = res[1];
      },
      error: err => console.error(err),
    })
  }
}
