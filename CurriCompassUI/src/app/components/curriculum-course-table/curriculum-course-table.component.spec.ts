import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CurriculumCourseTableComponent } from './curriculum-course-table.component';

describe('CurriculumCourseTableComponent', () => {
  let component: CurriculumCourseTableComponent;
  let fixture: ComponentFixture<CurriculumCourseTableComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CurriculumCourseTableComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CurriculumCourseTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
