import { Component } from '@angular/core';
import { NavigationComponent } from '../../components/navigation/navigation.component';
import { MainHeaderComponent } from '../main-header/main-header.component';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-baselayout',
  standalone: true,
  imports: [
    NavigationComponent,
    MainHeaderComponent,
    RouterOutlet,
  ],
  templateUrl: './baselayout.component.html',
  styleUrl: './baselayout.component.css'
})
export class BaselayoutComponent {

}
