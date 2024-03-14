import { TestBed } from '@angular/core/testing';

import { RemoveInputErrorService } from './remove-input-error.service';

describe('RemoveInputErrorService', () => {
  let service: RemoveInputErrorService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(RemoveInputErrorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
