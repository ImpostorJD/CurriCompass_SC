import { Component, inject } from '@angular/core';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions, yearLevel } from '../../../../configs/Constants';
import { RolesToRenderDirective } from '../../../services/auth/roles-to-render.directive';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { FormatDateService } from '../../../services/format/format-date.service';
import { StudentFilterPipe } from '../../../services/filter/search-filters/student-filter.pipe';

@Component({
  selector: 'app-consultation-page',
  standalone: true,
  imports: [
    RolesToRenderDirective,
    CommonModule,
    FormsModule,
    RouterLink,
    StudentFilterPipe
  ],
  providers:[],
  templateUrl: './consultation-page.component.html',
  styleUrl: './consultation-page.component.css'
})
export class ConsultationPageComponent {
  constructor(
    public dateformat: FormatDateService,
    public router: Router,
  ){}

  auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  studentSelected:any = null;
  currentSemSy: any = null;
  disableEnlistment = false;
  currentLogged : any  = null;
  searchConsultation:string = '';
  consultations: any = null;
  disabledButton:boolean = true;
  isAdmin = true;
  currentUnits = 0;
  showError = false;
  message = '';

  semSy:any = null;
  studentRecords:any = null;

  clickStudent(id :string){
    this.router.navigateByUrl('/consultation/' + id)
  }

  resetError(){
    this.showError = false;
    this.message = "";
  }

  generateEnlistment(){
    this.disableEnlistment = true;
    this.req.postResource('enlistment', {"srid" : this.currentLogged.student_record.student_no }, httpOptions(this.auth.getCookie('user')))
      .subscribe({
        next: () => {
          this.getUser();
        },
        error: (err:any) => {
          if (err.status == 400) {
            let error_messages = err.error.status;
            this.message = error_messages;
            this.showError = true;
            // setTimeout(() => {
            //   this.showError = false;
            //   this.messages = [];
            // }, 5000);
          }
        },
      });
  }

  getUser(){
    this.req.getResource('enlistment/' + this.currentLogged.student_record.student_no, httpOptions(this.auth.getCookie('user'))).subscribe((data:any)=>{
      this.studentSelected = data[1];
      this.currentSemSy = data[2];

      this.studentSelected.student_record?.enlistment[0]?.enlistment_subjects.forEach((data:any)=>{
        this.currentUnits += data.course_availability.subjects.subjectcredits;
      });
    });
  }

  retrieveStudents(){
    this.req.getResource('enlistment', httpOptions(this.auth.getCookie('user')))
        .subscribe((data:any) => {
          this.studentRecords = data[1].sort((a: any, b: any) => yearLevel(a.student_record.year_level.year_level_desc, b.student_record.year_level.year_level_desc));
        });
  }

  async ngOnInit() {
    this.req.getResource('semester-management/current', httpOptions(this.auth.getCookie('user')))
      .subscribe((data:any) => {
        this.semSy = data[1];
      });

    const resp = await this.auth.checkUserAsync();
    this.currentLogged = await resp[1];

    if (this.currentLogged) {

      for(let userRole of this.currentLogged!.user_roles) {

        if ("Student" == userRole.rolename) {
          this.isAdmin = false;
          break;
        }
      }
    }

    if(this.isAdmin) {
      this.retrieveStudents();
    }else{
      this.getUser();
    }
  }

}
