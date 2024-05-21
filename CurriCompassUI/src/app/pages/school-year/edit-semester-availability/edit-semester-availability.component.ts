import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { FormatDateService } from '../../../services/format/format-date.service';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-edit-semester-availability',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    RouterLink
  ],
  templateUrl: './edit-semester-availability.component.html',
  styleUrl: './edit-semester-availability.component.css'
})

export class EditSemesterAvailabilityComponent {
  constructor(
    private router: Router,
    private activeRouter: ActivatedRoute,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    public dateformat: FormatDateService,
  ){

  }
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);
  private routerId!:number;

  semesters: any = [];
  schoolYears: any = [];

  semsy = this.fb.group({
    semsyid : new FormControl('', [Validators.required]),
    sy : new FormControl('', [Validators.required]),
    semid : new FormControl('', [Validators.required]),
  });

  handleSubmit(){
    if(this.semsy.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.semsy);
      return;
    }

    this.req.patchResource('semester-management/' + this.routerId, this.semsy.value, httpOptions(this.auth.getCookie('user'))).subscribe({
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

  ngOnInit() {
    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.schoolYears = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.semesters = res[1];
      },
      error: err => console.error(err),
    });

    this.activeRouter.params.subscribe(params => {
      this.routerId = parseInt(params['id']);
      this.req.getResource('semester-management/' + this.routerId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (res:any) => {
          this.semsy.patchValue(res[1]);
        },
        error: err => console.error(err),
      });
    });
  }
}
