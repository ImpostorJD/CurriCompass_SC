import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { RouterOutlet } from '@angular/router';
import { LoginUiComponent } from "./login-ui/login-ui.component";


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet,LoginUiComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'CurriCompassUI';
}
