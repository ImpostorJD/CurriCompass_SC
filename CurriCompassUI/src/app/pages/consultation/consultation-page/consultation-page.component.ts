import { Component, inject } from '@angular/core';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { RolesToRenderDirective } from '../../../services/auth/roles-to-render.directive';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { FormatDateService } from '../../../services/format/format-date.service';

@Component({
  selector: 'app-consultation-page',
  standalone: true,
  imports: [
    RolesToRenderDirective,
    CommonModule,
    FormsModule,
    RouterLink
  ],
  templateUrl: './consultation-page.component.html',
  styleUrl: './consultation-page.component.css'
})
export class ConsultationPageComponent {
  constructor(
    public dateformat: FormatDateService,
  ){}

  auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  searchConsultation:string = '';
  consultations: any = null;
  disabledButton:boolean = true;

  studentRecords:any = null;

  deleteConsultation(index:number) {
    this.req.deleteResource('consultation' + index, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.getConsultations();
      },

      error: err => console.error(err),
    })
  }

  async updateEnlistment(){
    const user = await this.auth.getUser();
    for(let userRole of user?.user_roles) {
      if (userRole.rolename.includes("Student")){
        this.req.postResource('enlistment', {
          'student_no' : user.student_no
        }, httpOptions(this.auth.getCookie('user'))).subscribe({
          next: (res:any) => {
            console.log(res);
            this.getStudentRecord(user);
          },
          error: err => console.error(err),
        })

        return;
      }
    }
  }

  getStudentRecord(user:any){
    this.req.getResource('enlistment/student-regular/' + user?.student_record.student_no, httpOptions(this.auth.getCookie('user')))
    .subscribe({
      next: (res:any) => {
        this.studentRecords = res[1];
        this.disabledButton = res[2].hasLatest;
      },
      error: err => console.error(err),
    });
  }

  getConsultations(){
    this.req.getResource('consultation', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.consultations = res[1];
      },

      error: err => console.error(err),
    })
  }

  async ngOnInit() {
    const user = await this.auth.getUser();

    for(let userRole of user?.user_roles) {
      if (userRole.rolename.includes("Student")){
        this.getStudentRecord(user);
        return;
      }
    }

    this.getConsultations();

  }
}
