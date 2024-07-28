import { Component, inject } from '@angular/core';
import { RolesToRenderDirective } from '../../../services/auth/roles-to-render.directive';
import { FormatDateService } from '../../../services/format/format-date.service';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../../services/auth/auth.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';
import { SystemLoadingService } from '../../../services/system-loading.service';

@Component({
  selector: 'app-profile-page',
  standalone: true,
  imports: [
    RolesToRenderDirective,
    CommonModule,
    LoadingComponentComponent,
  ],
  providers: [FormatDateService],
  templateUrl: './profile-page.component.html',
  styleUrl: './profile-page.component.css'
})
export class ProfilePageComponent {
  constructor(
    public formatDate: FormatDateService,
    public loading: SystemLoadingService
  ){}

  auth: AuthService = inject(AuthService);
  curriculumSubjects:any = [];
  user:any;

  getSubjectGrade(coursecode: string){
    const course  = this.user.student_record.subjects_taken.find((s:any) => s.coursecode == coursecode)
    return course ? course.grade : '';
  }

  totalSum(index:number, controlname:string){
    let total = 0;

    this.curriculumSubjects[index]['subjects'].forEach((c:any) => {
      const value = parseFloat(c[controlname]);
      total+= value;
    });

    return Math.round(total * 100) /100;
  }
  async ngOnInit(){
    this.loading.initLoading();
    this.user = await this.auth.getUser();
    if(this.user.student_record){
      this.user.student_record.curriculum.curriculum_subjects.forEach((e: any) => {
        const table:any = this.curriculumSubjects.find((a:any) => a.semester == e.semid && a.year == e.year_level_id)

        if(table){
          table['subjects'].push(e);
        }else{
          const table:any = {"year" : e.year_level_id, "semester" : e.semid, "subjects" : []}
          table['subjects'].push(e);
          this.curriculumSubjects.push(table);
        }

      });
      this.curriculumSubjects.sort((a:any, b:any) => {
        const aLevel = a.year;
        const bLevel = b.year;
        const aSemester = a.semester;
        const bSemester = b.semester;

        if (aLevel === bLevel) {
          return aSemester - bSemester;
        }
        return aLevel - bLevel;
      });
    }

    this.loading.endLoading();
  }
}
