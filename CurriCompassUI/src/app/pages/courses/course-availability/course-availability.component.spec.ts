import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CourseAvailabilityComponent } from './course-availability.component';

describe('CourseAvailabilityComponent', () => {
  let component: CourseAvailabilityComponent;
  let fixture: ComponentFixture<CourseAvailabilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CourseAvailabilityComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CourseAvailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
