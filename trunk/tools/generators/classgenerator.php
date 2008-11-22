<?php

class ClassGenerator
{
	private $request = array();
	private $error = '';

	private $dataType = array('integer' => '0', 'string'  => '\'\'', 'array' => 'array()');

	private $className = '';
	private $classExtends = '';
	private $classImplements = '';
	private $classIncludes = '';
	private $classDescription = '';

	private $propertyNameList = array();
	private $propertyTypeList = array();
	private $propertyDbNameList = array();

	private $output = '';
	private $propertyOutput = '';
	private $methodOutput = '';

	public function __construct($GET)
	{
		$this->request = $GET;

		try {
			$this->assignRequest();
			$this->createClass();
		} catch (Exception $e) {
			$this->error = $e->getMessage();
		}

	}

	private function assignRequest()
	{
		$this->setClassName()
			->setClassExtends()
			->setClassImplements()
			->setClassIncludes()
			->setClassDescription()
			->setPropertyNameList()
			->setPropertyTypeList()
			->setPropertyDbNameList();
	}

	private function createClass()
	{
		$this->setDescription();
		$this->setClassHeader();
		$this->setProperties();
	}

	private function setClassHeader()
	{
		$this->addOutput("\nclass " . $this->className);
		if (FALSE === empty($this->classExtends)) {
			$this->addOutput(" extends " . $this->classExtends);
		}
		if (FALSE === empty($this->classImplements)) {
			$this->addOutput(" implements " . $this->classImplements);
		}
		$this->addOutput("\n{\n");
	}

	private function setProperties()
	{
		$count = count($this->propertyNameList);
		for ($i=0;$i <= $count; $i++) {
			if (FALSE === empty($this->propertyNameList[$i])) {
				$this->addPropertyOutput("\n\tprivate $" . $this->propertyNameList[$i] . " = ");
				if (isset($this->dataType[$this->propertyTypeList[$i]])) {
					$this->addPropertyOutput($this->dataType[$this->propertyTypeList[$i]] . ";\n");
					// Pridame metodu
					$this->setMethodGetter($this->propertyNameList[$i], $this->propertyTypeList[$i]);
					$this->setMethodSetter($this->propertyNameList[$i], $this->propertyTypeList[$i]);
				} else {
					$this->addPropertyOutput("NULL;\n");
				}
			}
		}
	}

	private function setDescription()
	{
		if (FALSE === empty($this->classDescription)) {
			$description = explode("\n", $this->classDescription);
			$output = "\n/**\n";
			foreach($description as $row){
				$output .= " * " . $row . "\n";
			}
			$output .= " */";
			$this->addOutput($output);
		}
		return $this;
	}

	private function setMethodGetter($propertyName, $dataType)
	{
		$output = "\n\t/**\n";
		$output .= "\t * Vrati hodnotu vlastnosti $" . "$propertyName\n";
		$output .= "\t *\n";
		$output .= "\t * @param void\n";
		$output .= "\t * @return $dataType\n";
		$output .= "\t */\n";
		$output .= "\tpublic function get" . ucfirst($propertyName);
		$output .= "()\n\t{\n\t\t";
		$output .= "return $" . "this->" . $propertyName . ";";
		$output .= "\n\t}\n";

		$this->addMethodOutput($output);
		return $this;
	}
	private function setMethodSetter($propertyName, $dataType)
	{

		switch ($dataType){
			case 'integer':
				$bool = "! is_numeric($" . $propertyName . ")";
				$exceptionText = "Promena $" . $propertyName . " musi byt datoveho typu integer.";
				$reType = "(int)";
			break;

			case 'string':
				$bool = "! is_string($" . $propertyName . ") && ! is_numeric($" . $propertyName . ")";
				$exceptionText = "Promena $" . $propertyName . " musi byt datoveho typu string.";
				$reType = "(string)";
			break;

			case 'array':
				$bool = "! is_array($" . $propertyName . ")";
				$exceptionText = "Promena $" . $propertyName . " musi byt datoveho typu array.";
				$reType = "(array)";
			break;

			default:
				$bool = "! empty($" . $propertyName . ")";
				$exceptionText = "Promena $" . $propertyName . " nesmi byt prazdna.";
				$reType = "";
			break;

		}

		$output = "\n\t/**\n";
		$output .= "\t * Nastavi hodnotu vlastnosti $" . "$propertyName\n";
		$output .= "\t *\n";
		$output .= "\t * @param $dataType\n";
		$output .= "\t * @return $dataType\n";
		$output .= "\t */\n";
		$output .= "\tprivate function set" . ucfirst($propertyName) . "($" . $propertyName . ")\n";
		$output .= "\t{\n";
			$output .= "\t\tif ($bool) {\n";
				$output .= "\t\t\tthrow new InvalidArgumentException('$exceptionText');\n";
			$output .= "\t\t}\n";
			$output .= "\t\t$" . $propertyName . " = $reType$" . $propertyName . ";\n";
			$output .= "\t\treturn $" . "this->$propertyName = $" . $propertyName . ";\n";
		$output .= "\t}\n";
		$this->addMethodOutput($output);
		return $this;
	}

	private function setClassName()
	{
		if (isset($this->request['className'])) {
			$this->className = trim($this->request['className']);
		}else{
			throw new Exception('Neni vyplnen nazev tridy');
		}
		return $this;
	}

	private function setClassExtends()
	{
		if (isset($this->request['classExtends'])) {
			$this->classExtends = trim($this->request['classExtends']);
		}
		return $this;
	}

	private function setClassImplements()
	{
		if (isset($this->request['classImplements'])) {
			$this->classImplements = trim($this->request['classImplements']);
		}
		return $this;
	}

	private function setClassIncludes()
	{
		if (isset($this->request['classIncludes'])) {
			$this->classIncludes = trim($this->request['classIncludes']);
		}
		return $this;
	}

	private function setClassDescription()
	{
		if (isset($this->request['classDescription'])) {
			$this->classDescription = trim($this->request['classDescription']);
		}
		return $this;
	}

	private function setPropertyNameList()
	{
		if (isset($this->request['propertyName']) && (FALSE === empty($this->request['propertyName'][0]))) {
			$this->propertyNameList = $this->request['propertyName'];
		} else {
			throw new Exception('Neni vyplnena zadna vlastnost objektu');
		}
		return $this;
	}

	private function setPropertyTypeList()
	{
		if (isset($this->request['propertyType']) && (FALSE === empty($this->request['propertyType'][0]))) {
			$this->propertyTypeList = $this->request['propertyType'];
		} else {
			throw new Exception('Neni vyplnen zadny datovy typ vlastnosti objektu');
		}
		return $this;
	}

	private function setPropertyDbNameList()
	{
		if (isset($this->request['propertyDbName'])) {
			$this->propertyDbNameList = $this->request['propertyDbName'];
		}
		return $this;
	}

	private function addOutput($output)
	{
		return $this->output .= $output;
	}

	private function addPropertyOutput($output)
	{
		return $this->propertyOutput .= $output;
	}

	private function addMethodOutput($output)
	{
		return $this->methodOutput .= $output;
	}

	public function __toString()
	{
		if (empty($this->error)) {
			$this->addOutput($this->propertyOutput . $this->methodOutput . "\n}");
			return $this->output;
		} else {
			return $this->error;
		}
	}
}