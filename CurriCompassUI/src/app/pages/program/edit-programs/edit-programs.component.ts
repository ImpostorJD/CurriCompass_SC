import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-edit-programs',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
    LoadingComponentComponent,
  ],
  templateUrl: './edit-programs.component.html',
  styleUrl: './edit-programs.component.css'
})
export class EditProgramsComponent {
  constructor(
    private fb: FormBuilder,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    public loading: SystemLoadingService
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  routerId: number = null!;
  programsField = this.fb.group({
    'programcode': new FormControl('', [Validators.required]),
    'programdesc': new FormControl('', [Validators.required]),
  });

  handleSubmit() {
    this.req.patchResource('programs/' + this.routerId, this.programsField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/programs')
      },

      error: err => {
        if(err.status == 409) {
          this.programsField.get('programcode')?.setErrors({duplicate: true});
        }
      }
    })

  }

  ngOnInit(){
    this.loading.initLoading();
    this.activatedRoute.params.subscribe(params => {
      this.routerId = parseInt(params['id']);

      this.req.getResource('programs/' + this.routerId, httpOptions(this.auth.getCookie('user'))).subscribe({
        next: (res: any) => {
          this.programsField.patchValue(res[1]);
          this.loading.endLoading();
        },
        error: err => console.error(err),
      })
    });

  }
}
