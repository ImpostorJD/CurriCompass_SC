import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { AuthService } from '../../../services/auth/auth.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';
import { SystemLoadingService } from '../../../services/system-loading.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { limitValidator } from '../../../services/validators/limit-validator';

@Component({
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    CommonModule,
    FormsModule,
    CourseFilterPipe,
    LoadingComponentComponent,
  ],
  providers: [
    CoursesServiceService,
    CourseFilterPipe,
  ],
  selector: 'app-edit-course-availability',
  templateUrl: './edit-course-availability.component.html',
  styleUrl: './edit-course-availability.component.css'
})
export class EditCourseAvailabilityComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private coursesService: CoursesServiceService,
    private fac: FormArrayControlUtilsService,
    private coursePipe: CourseFilterPipe,
    public dateformat: FormatDateService,
    public loading: SystemLoadingService,
    private activatedRoute: ActivatedRoute
  ){

  }
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  searchCourse: string = '';
  selectedCurricula:string|null = "";

  routerId = null;
  semsy: any = null;
  timeSlot = {
    lab: [
      {
        value: '8-11',
        text: '8:00 AM - 11:00 AM'
      },
      {
        value: '11-2',
        text: '11:00 AM - 2:00 PM'
      },
      {
        value: '2-5',
        text: '2:00 PM - 5:00 PM'
      },
    ],
    lec: [
      {
        value: '8-10',
        text: '8:00 AM - 10:00 AM'
      },
      {
        value: '9-11',
        text: '9:00 AM - 11:00 AM'
      },
      {
        value: '10-12',
        text: '10:00 AM - 12:00 PM'
      },
      {
        value: '1-3',
        text: '1:00 PM - 3:00 PM'
      },
      {
        value: '2-4',
        text: '2:00 PM - 4:00 PM'
      },
      {
        value: '3-5',
        text: '3:00 PM - 5:00 PM'
      },
    ]

  }

  courseAvailability =  this.fb.group({
    coursecode: new FormControl(''),
    semsyid: new FormControl('', [Validators.required]),
    time: new FormControl('', [Validators.required]),
    section: new FormControl('', [Validators.required]),
    lab: new FormControl(false),
    section_limit: new FormControl(0, [Validators.required, limitValidator]),
    days: new FormControl('', [Validators.required]),
  });

  isLabHoursGreaterThanLecHours(): boolean {
    return this.courseAvailability.get('lab')?.value as boolean;
  }


  handleSubmit(){
    if(this.semsy.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.courseAvailability);
      return;
    }
    console.log(this.courseAvailability.value);

    this.req.patchResource('course-availability/' + this.routerId, this.courseAvailability.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/course-availability');
      },
      error: (err:any) => {
        if(err.status == 409){
          this.courseAvailability.get('coursecode')!.setErrors({'duplicate' :true});
        }
      }
    });


  }

  ngOnInit(){
    this.loading.initLoading();
    this.req.getResource('semester-management', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (s:any) => {
        this.semsy = s[1];
      },
      error: (err:any) => console.log(err),

    });
    this.activatedRoute.params.subscribe(params => {
      this.routerId = params['id'];
      this.req.getResource('course-availability/' + this.routerId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (ca:any) => {
          this.courseAvailability.patchValue(ca[1]);
          this.loading.endLoading();
        },
        error: (err:any) => console.log(err),
      })
    })



  }

}
