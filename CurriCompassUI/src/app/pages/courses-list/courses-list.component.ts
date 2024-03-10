import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-courses-list',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './courses-list.component.html',
  styleUrl: './courses-list.component.css'
})
export class CoursesListComponent {
  iterates: Array<number> = Array.from({ length: 20 }, (_, i) => i + 1);
}
