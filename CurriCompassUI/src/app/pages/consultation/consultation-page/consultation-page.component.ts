import { Component, inject } from '@angular/core';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { httpOptions } from '../../../../configs/Constants';
import { RolesToRenderDirective } from '../../../services/auth/roles-to-render.directive';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';

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
  constructor(){}

  auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  searchConsultation:string = '';
  consultations: any = null;

  async ngOnInit() {
    const user = await this.auth.getUser();

    for(let userRole of user?.user_roles) {
      if (userRole.rolename.includes("Student")){
        return;
      }
    }

    this.req.getResource('consultation', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any) => {
        this.consultations = res[1];
      },

      error: err => console.error(err),
    })

  }
}
