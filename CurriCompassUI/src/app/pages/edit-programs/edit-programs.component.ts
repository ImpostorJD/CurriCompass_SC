import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormBuilder, FormControl, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';
import { httpOptions } from '../../../configs/Constants';

@Component({
  selector: 'app-edit-programs',
  standalone: true,
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    CommonModule,
    RouterLink,
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService
  ],
  templateUrl: './edit-programs.component.html',
  styleUrl: './edit-programs.component.css'
})
export class EditProgramsComponent {
  constructor(
    private req: HttpReqHandlerService,
    private fb: FormBuilder,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    public rs: RemoveInputErrorService,
  ){}

  routerId: number = null!;
  programsField = this.fb.group({
    'programcode': new FormControl('', [Validators.required]),
    'programdesc': new FormControl('', [Validators.required]),
  });

  handleSubmit() {
    this.req.patchResource('programs/' + this.routerId, this.programsField.value, httpOptions).subscribe({
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
    this.activatedRoute.params.subscribe(params => {
      this.routerId = parseInt(params['id']);

      this.req.getResource('programs/' + this.routerId, httpOptions).subscribe({
        next: (res: any) => {
          this.programsField.patchValue(res[1]);
        },
        error: err => console.error(err),
      })
    });

  }
}
