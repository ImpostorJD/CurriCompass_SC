import { TestBed } from '@angular/core/testing';

import { FormArrayControlUtilsService } from './form-array-control-utils.service';

describe('FormArrayControlUtilsService', () => {
  let service: FormArrayControlUtilsService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(FormArrayControlUtilsService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
