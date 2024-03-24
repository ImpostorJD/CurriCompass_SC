import { Component, inject } from '@angular/core';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { httpOptions } from '../../../../configs/Constants';
import { AuthService } from '../../../services/auth/auth.service';

@Component({
  selector: 'app-programs-form',
  standalone: true,
  imports: [
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
  ],
  providers: [
    RemoveInputErrorService
  ],
  templateUrl: './programs-form.component.html',
  styleUrl: './programs-form.component.css'
})
export class ProgramsFormComponent {

  constructor(
    private fb: FormBuilder,
    private router: Router,
  ){}

  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  programsField = this.fb.group({
    'programcode': new FormControl('', [Validators.required]),
    'programdesc': new FormControl('', [Validators.required]),
  });

  handleSubmit() {
    this.req.postResource('programs', this.programsField.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/programs')
      },

      error: err => {
        if(err.status == 409) {
          console.log(err.status);
          this.programsField.get('programcode')?.setErrors({duplicate: true});
        }
      }
    })

  }
}
