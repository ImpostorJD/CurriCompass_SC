import { Component, inject } from '@angular/core';
import { RouterLink } from '@angular/router';
import { EllipsisPipe } from '../../services/format/ellipsis.pipe';
import { AuthService } from '../../services/auth/auth.service';

@Component({
  selector: 'app-main-header',
  standalone: true,
  imports: [RouterLink, EllipsisPipe],
  templateUrl: './main-header.component.html',
  styleUrl: './main-header.component.css'
})
export class MainHeaderComponent {
  constructor(){}

  auth: AuthService = inject(AuthService);

  user:any;

  async ngOnInit() {
    this.user = await this.auth.getUser();
  }
}
