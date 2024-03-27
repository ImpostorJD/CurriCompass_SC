import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { RouterLink } from '@angular/router';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';

@Component({
  selector: 'app-add-consultation',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
  ],
  templateUrl: './add-consultation.component.html',
  styleUrl: './add-consultation.component.css'
})
export class AddConsultationComponent {

  constructor(
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  semesters: any = null;
  schoolYears: any = null;
  curricula: any = null;

  semConsultation = this.fb.group({
    semSubjects: this.fb.array([]),
  });

  addSemSubject(){
    const subjectField = this.fb.group({
      "subjectid": new FormControl(null),
      "time": new FormControl(null),
      "days": new FormControl(null),
    });

    this.fac.addControl(this.semSubjects, subjectField);
  }

  popSemSubject(index: number){
    this.fac.popControl(this.semSubjects, index);
  }


  get semSubjects(): FormArray{
    return this.semConsultation.get('semSubjects') as FormArray;
  }

  handleSubmit(){
    console.log("Submit works");
  }

}
