import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AddSemesterAvailabilityComponent } from './add-semester-availability.component';

describe('AddSemesterAvailabilityComponent', () => {
  let component: AddSemesterAvailabilityComponent;
  let fixture: ComponentFixture<AddSemesterAvailabilityComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AddSemesterAvailabilityComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(AddSemesterAvailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
